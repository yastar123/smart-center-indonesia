// Fallback: ensure global overlays don't block Bootstrap modals
document.addEventListener("DOMContentLoaded", function () {
    var pageLoader = document.getElementById("pageLoader");
    var sidebarOverlay = document.getElementById("sidebarOverlay");

    // Bootstrap appends modal backdrops directly to <body>. If a modal is
    // rendered inside a positioned/z-indexed wrapper, the backdrop can sit
    // above it and block every click. Keep modals as body children so their
    // z-index is compared in the same stacking context as the backdrop.
    document.querySelectorAll(".modal").forEach(function (modalEl) {
        if (modalEl.parentElement !== document.body) {
            document.body.appendChild(modalEl);
        }
    });

    // Safe backdrop remover — only removes backdrops when no Bootstrap modal is active
    function removeStaleBackdrops() {
        if (document.body.classList.contains("modal-open")) return;
        try {
            document.querySelectorAll(".modal-backdrop").forEach(function (el) {
                el.remove();
            });
        } catch (e) {}
    }

    // Run immediately to clear any pre-existing stale backdrops
    removeStaleBackdrops();

    // Observe DOM mutations — only remove backdrops that appear while no modal is open
    try {
        new MutationObserver(function (mutations) {
            if (document.body.classList.contains("modal-open")) return;
            mutations.forEach(function (m) {
                m.addedNodes.forEach(function (n) {
                    try {
                        if (n && n.nodeType === 1) {
                            // Remove extension-injected overlays
                            if (
                                n.matches &&
                                (n.matches(".injected-overlay") ||
                                    n.matches(".extension-overlay"))
                            ) {
                                n.remove();
                                return;
                            }
                            // Remove backdrop only when no modal is currently active
                            if (
                                n.matches &&
                                n.matches(".modal-backdrop") &&
                                !document.body.classList.contains("modal-open")
                            ) {
                                n.remove();
                                return;
                            }
                            // If a node has an extremely high z-index and covers the screen, disable pointer events
                            var z = window.getComputedStyle(n).zIndex;
                            if (z && !isNaN(Number(z)) && Number(z) > 9000) {
                                n.style.pointerEvents = "none";
                            }
                        }
                    } catch (e) {}
                });
            });
        }).observe(document.body, { childList: true, subtree: false });
    } catch (e) {}

    function hideLoader() {
        if (!pageLoader) return;
        pageLoader.classList.remove("show");
        pageLoader.style.display = "none";
        pageLoader.style.pointerEvents = "none";
    }
    function showLoader() {
        if (!pageLoader) return;
        pageLoader.style.display = "";
    }

    document.querySelectorAll(".modal").forEach(function (modalEl) {
        modalEl.addEventListener("show.bs.modal", function () {
            hideLoader();
            if (sidebarOverlay && sidebarOverlay.classList.contains("show")) {
                sidebarOverlay.classList.remove("show");
                var sidebar = document.getElementById("sidebar");
                if (sidebar) sidebar.classList.remove("show");
                document.body.style.overflow = "";
            }
        });
        modalEl.addEventListener("hidden.bs.modal", function () {
            showLoader();
            // Clean up any stale backdrops after modal closes
            setTimeout(removeStaleBackdrops, 50);
        });
    });

    // AJAX submit handler for Add Modal (fallback when inline handlers blocked)
    (function attachAjaxSubmit() {
        var addForm = document.querySelector("#addModal form");
        if (!addForm) return;

        function clearErrors() {
            addForm.querySelectorAll(".is-invalid").forEach(function (el) {
                el.classList.remove("is-invalid");
            });
            addForm
                .querySelectorAll(".invalid-feedback")
                .forEach(function (el) {
                    el.remove();
                });
        }

        function showErrors(errors) {
            Object.keys(errors).forEach(function (k) {
                var field = addForm.querySelector('[name="' + k + '"]');
                if (!field) return;
                field.classList.add("is-invalid");
                var msg = document.createElement("div");
                msg.className = "invalid-feedback";
                msg.innerText = Array.isArray(errors[k])
                    ? errors[k].join("\n")
                    : errors[k];
                if (field.parentNode) field.parentNode.appendChild(msg);
            });
        }

        addForm.addEventListener(
            "submit",
            function (ev) {
                ev.preventDefault();
                clearErrors();
                var action =
                    addForm.getAttribute("action") || window.location.href;
                var fd = new FormData(addForm);
                var token = document
                    .querySelector('meta[name="csrf-token"]')
                    ?.getAttribute("content");

                fetch(action, {
                    method: "POST",
                    body: fd,
                    credentials: "same-origin",
                    headers: token
                        ? {
                              "X-CSRF-TOKEN": token,
                              "X-Requested-With": "XMLHttpRequest",
                          }
                        : { "X-Requested-With": "XMLHttpRequest" },
                })
                    .then(function (r) {
                        if (r.status === 422)
                            return r.json().then(function (j) {
                                throw { validation: j };
                            });
                        if (!r.ok) throw r;
                        return r.text();
                    })
                    .then(function () {
                        try {
                            var bm = bootstrap.Modal.getInstance(
                                document.getElementById("addModal"),
                            );
                            if (bm) bm.hide();
                        } catch (e) {}
                        window.location.reload();
                    })
                    .catch(function (err) {
                        if (err && err.validation && err.validation.errors) {
                            showErrors(err.validation.errors);
                            return;
                        }
                        console.error("submit error", err);
                    });
            },
            { passive: false },
        );
    })();

    // Programmatic open for addModal to avoid extension/data-attribute interference
    document
        .querySelectorAll('[data-bs-target="#addModal"]')
        .forEach(function (btn) {
            btn.addEventListener(
                "click",
                function (ev) {
                    ev.preventDefault();
                    ev.stopImmediatePropagation();
                    hideLoader();
                    var modalEl = document.getElementById("addModal");
                    if (!modalEl) return;
                    try {
                        var instance =
                            bootstrap.Modal.getInstance(modalEl) ||
                            new bootstrap.Modal(modalEl);
                        instance.show();
                    } catch (e) {
                        console.error("modal show error", e);
                    }
                },
                true,
            );
        });
});
