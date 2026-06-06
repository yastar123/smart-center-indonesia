// Fallback: ensure global overlays don't block Bootstrap modals
document.addEventListener("DOMContentLoaded", function () {
    var pageLoader = document.getElementById("pageLoader");
    var sidebarOverlay = document.getElementById("sidebarOverlay");
    // Aggressive backdrop remover (handles injected overlays from extensions)
    function removeAllBackdrops() {
        try {
            document.querySelectorAll(".modal-backdrop").forEach(function (el) {
                el.remove();
            });
        } catch (e) {}
        try {
            document.querySelectorAll(".modal-backdrop").forEach(function (el) {
                el.style.pointerEvents = "none";
            });
        } catch (e) {}
    }

    // Run immediately to clear any existing injected backdrops
    removeAllBackdrops();

    // Observe DOM mutations to remove any newly injected backdrops/overlays
    try {
        new MutationObserver(function (mutations) {
            mutations.forEach(function (m) {
                m.addedNodes.forEach(function (n) {
                    try {
                        if (n && n.nodeType === 1) {
                            if (
                                n.matches &&
                                (n.matches(".modal-backdrop") ||
                                    n.matches(".injected-overlay") ||
                                    n.matches(".extension-overlay"))
                            ) {
                                n.remove();
                                return;
                            }
                            // If a node has an extremely high z-index and covers the screen, disable pointer events
                            var z = window.getComputedStyle(n).zIndex;
                            if (z && !isNaN(Number(z)) && Number(z) > 1500) {
                                n.style.pointerEvents = "none";
                            }
                        }
                    } catch (e) {}
                });
            });
            // also ensure no leftover backdrops
            removeAllBackdrops();
        }).observe(document.body, { childList: true, subtree: true });
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
            // hide any global loader or overlay that may intercept clicks
            hideLoader();
            if (sidebarOverlay && sidebarOverlay.classList.contains("show")) {
                sidebarOverlay.classList.remove("show");
                var sidebar = document.getElementById("sidebar");
                if (sidebar) sidebar.classList.remove("show");
                document.body.style.overflow = "";
            }
            // ensure backdrop above any leftover overlays
            setTimeout(function () {
                var backdrop = document.querySelector(".modal-backdrop");
                if (backdrop) backdrop.style.zIndex = 1060;
                var modals = document.querySelectorAll(".modal");
                modals.forEach(function (m) {
                    m.style.zIndex = 1065;
                });
            }, 1);
        });
        modalEl.addEventListener("hidden.bs.modal", function () {
            // restore loader display (but keep it non-blocking)
            showLoader();
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
                        // success: close modal and reload to refresh list
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
