<?php
require_once "db.php";
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    $conn->query("DELETE FROM courses WHERE id = $id");
    header("Location: view_courses.php");
    exit();
}
$result = $conn->query("SELECT * FROM courses ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>View Courses | The Editorial Scholar</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Manrope:wght@700;800;900&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            colors: {
              "background": "#f8f9fa",
              "surface-container-low": "#f3f4f5",
              "surface-container-high": "#e7e8e9",
              "surface": "#f8f9fa",
              "on-surface-variant": "#424656",
              "on-surface": "#191c1d",
              "surface-container-lowest": "#ffffff",
              "error": "#ba1a1a",
              "outline": "#737688",
              "primary": "#0048c9",
              "primary-fixed": "#dbe1ff",
            },
            fontFamily: {
              "headline": ["Manrope"],
              "body": ["Inter"],
              "label": ["Inter"]
            },
          },
        },
      }
    </script>
<style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, .brand-font { font-family: 'Manrope', sans-serif; }
    </style>
  </head>
<body class="bg-background text-on-surface min-h-screen">
<header class="bg-white/80 backdrop-blur-xl shadow-sm fixed top-0 w-full z-50">
<div class="flex justify-between items-center px-8 h-20 max-w-7xl mx-auto w-full">
<div class="flex items-center gap-3">
<span class="material-symbols-outlined text-blue-700 text-3xl">school</span>
<span class="text-2xl font-extrabold tracking-tight text-blue-700 font-headline">The Editorial Scholar</span>
</div>
<nav class="hidden md:flex gap-8 items-center font-headline">
<a class="text-slate-600 hover:text-blue-600 transition-colors" href="index.php">Home</a>
<a class="text-slate-600 hover:text-blue-600 transition-colors" href="add_course.php">Add Course</a>
<a class="text-blue-700 border-b-2 border-blue-700 pb-1 font-medium" href="view_courses.php">View Courses</a>
</nav>
</div>
<div class="bg-slate-200/50 h-[1px]"></div>
</header>
<main class="pt-32 pb-20 px-8 max-w-7xl mx-auto">
<div class="mb-12">
<h1 class="text-5xl font-black font-headline text-on-surface tracking-tight mb-4">Course Inventory</h1>
<p class="text-on-surface-variant text-lg max-w-2xl leading-relaxed font-body">Manage and audit the active curriculum. Review faculty assignments, credit allocations, and maintain the intellectual standards of the academy.</p>
</div>
<section class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden ring-1 ring-black/[0.02]">
<div class="overflow-x-auto">
<table class="w-full text-left border-collapse font-body">
<thead>
<tr class="bg-surface-container-low">
<th class="px-8 py-5 text-sm font-semibold text-on-surface uppercase tracking-wider">Course Name</th>
<th class="px-8 py-5 text-sm font-semibold text-on-surface uppercase tracking-wider text-center">Code</th>
<th class="px-8 py-5 text-sm font-semibold text-on-surface uppercase tracking-wider">Instructor</th>
<th class="px-8 py-5 text-sm font-semibold text-on-surface uppercase tracking-wider text-center">Credits</th>
<th class="px-8 py-5 text-sm font-semibold text-on-surface uppercase tracking-wider text-right">Actions</th>
</tr>
</thead>
<tbody class="divide-y divide-surface-container">
<?php if ($result && $result->num_rows > 0): ?>
    <?php while($row = $result->fetch_assoc()): ?>
    <tr class="hover:bg-blue-50/30 transition-colors group">
        <td class="px-8 py-6">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-lg bg-primary-fixed flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined">auto_stories</span>
                </div>
                <span class="font-bold text-on-surface text-lg"><?php echo htmlspecialchars($row['course_name']); ?></span>
            </div>
        </td>
        <td class="px-8 py-6 text-center">
            <span class="bg-surface-container-high px-3 py-1 rounded-md text-xs font-mono font-bold text-on-surface-variant"><?php echo htmlspecialchars($row['course_code']); ?></span>
        </td>
        <td class="px-8 py-6">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-gray-400">person</span>
                <span class="text-on-surface-variant font-medium"><?php echo htmlspecialchars($row['instructor']); ?></span>
            </div>
        </td>
        <td class="px-8 py-6 text-center font-semibold text-primary"><?php echo htmlspecialchars($row['credits']); ?>.0</td>
        <td class="px-8 py-6 text-right">
            <button type="button" onclick="confirmDelete(<?php echo $row['id']; ?>, '<?php echo addslashes(htmlspecialchars($row['course_code'])); ?>')" class="p-2 text-error hover:bg-red-100 rounded-full transition-all active:scale-90" title="Delete Course">
                <span class="material-symbols-outlined">delete</span>
            </button>
        </td>
    </tr>
    <?php endwhile; ?>
<?php else: ?>
    <tr><td colspan="5" class="px-8 py-6 text-center text-on-surface-variant">No courses found matching the criteria.</td></tr>
<?php endif; ?>
</tbody>
</table>
</div>
<div class="bg-surface-container-low px-8 py-4 flex justify-between items-center text-sm font-medium text-on-surface-variant font-body">
<span>Showing <?php echo $result ? $result->num_rows : 0; ?> active courses</span>
</div>
</section>
</main>
<script src="script.js"></script>
</body></html>
