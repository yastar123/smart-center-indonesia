<html>
<head>
    <meta charset="utf-8">
    <style>
        table { width:100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background: #f4f4f4; }
    </style>
    <title>Branches</title>
</head>
<body>
    <h3>Branches</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Address</th>
                <th>City</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($b->id); ?></td>
                <td><?php echo e($b->name); ?></td>
                <td><?php echo e($b->address); ?></td>
                <td><?php echo e($b->city); ?></td>
                <td><?php echo e($b->phone); ?></td>
                <td><?php echo e($b->email); ?></td>
                <td><?php echo e($b->status); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</body>
</html>
<?php /**PATH C:\Users\Edu Juanda Pratama\Downloads\smart-center-indonesia-1 (2)\smart-center-indonesia\resources\views/owner/branches/pdf.blade.php ENDPATH**/ ?>