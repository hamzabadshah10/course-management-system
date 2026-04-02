<?php
require_once "db.php";
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $courseName = $_POST["courseName"] ?? "";
    $courseCode = $_POST["courseCode"] ?? "";
    $instructor = $_POST["instructor"] ?? "";
    $credits = $_POST["credits"] ?? 0;
    $description = $_POST["description"] ?? "";
    
    $sql = "INSERT INTO courses (course_name, course_code, instructor, credits, description) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssis", $courseName, $courseCode, $instructor, $credits, $description);
    
    if ($stmt->execute()) {
        $message = "<div class='p-4 rounded-xl text-sm flex items-center gap-3 bg-green-100 text-green-800'><span class='material-symbols-outlined'>check_circle</span> Course successfully established.</div>";
    } else {
        $message = "<div class='p-4 rounded-xl text-sm flex items-center gap-3 bg-red-100 text-red-800'><span class='material-symbols-outlined'>error</span> Error: " . $conn->error . "</div>";
    }
}
?>
<!DOCTYPE html>
<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Add New Course | The Editorial Scholar</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet"/>
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
      .linear-polish { background: linear-gradient(135deg, #0048c9 0%, #005dff 100%); }
    </style>
  </head>
<body class="bg-background text-on-surface">
<!-- Top Navigation -->
<header class="bg-white/80 backdrop-blur-xl shadow-sm fixed top-0 w-full z-50">
<div class="flex justify-between items-center px-8 h-20 max-w-7xl mx-auto w-full">
<div class="flex items-center gap-3">
<span class="material-symbols-outlined text-blue-700 text-3xl">school</span>
<span class="text-2xl font-extrabold tracking-tight text-blue-700">The Editorial Scholar</span>
</div>
<nav class="hidden md:flex gap-8 items-center font-headline">
<a class="text-slate-600 hover:text-blue-600 transition-colors" href="index.php">Home</a>
<a class="text-blue-700 border-b-2 border-blue-700 pb-1 font-medium" href="add_course.php">Add Course</a>
<a class="text-slate-600 hover:text-blue-600 transition-colors" href="view_courses.php">View Courses</a>
</nav>
</div>
<div class="bg-slate-200/50 h-[1px]"></div>
</header>
<main class="pt-32 pb-20 px-6 min-h-screen">
<div class="max-w-4xl mx-auto">
<div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-6">
<div class="max-w-xl">
<span class="text-primary font-bold tracking-widest text-xs uppercase mb-2 block font-headline">Curriculum Expansion</span>
<h1 class="text-5xl font-extrabold font-headline text-on-surface leading-tight">Create a New Scholarly Course</h1>
<p class="text-on-surface-variant mt-4 text-lg font-body">Define the parameters of knowledge. Use this interface to integrate new academic modules into the PAF-IAST ecosystem.</p>
</div>
</div>
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
<div class="lg:col-span-12">
<div class="bg-surface-container-lowest p-8 md:p-12 rounded-[2rem] shadow-[0_20px_40px_rgba(0,72,201,0.06)]">
<form class="space-y-8 font-body" id="addCourseForm" method="POST" action="add_course.php" novalidate>
<?php echo $message; ?>
<div class="hidden p-4 rounded-xl text-sm flex items-center gap-3" id="formStatus"></div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
<div class="flex flex-col gap-2">
<label class="font-semibold text-on-surface text-sm ml-1" for="courseName">Course Name</label>
<input class="w-full bg-surface-container-high border-none focus:ring-2 focus:ring-primary/20 rounded-xl p-4 transition-all outline-none" id="courseName" name="courseName" placeholder="Enter your Course name" type="text"/>
<span class="error-msg hidden text-error text-xs ml-1 mt-1">Please enter a valid course name.</span>
</div>
<div class="flex flex-col gap-2">
<label class="font-semibold text-on-surface text-sm ml-1" for="courseCode">Course Code</label>
<input class="w-full bg-surface-container-high border-none focus:ring-2 focus:ring-primary/20 rounded-xl p-4 transition-all outline-none uppercase" id="courseCode" name="courseCode" placeholder="AFD-402" type="text"/>
<span class="error-msg hidden text-error text-xs ml-1 mt-1">Course code is required.</span>
</div>
<div class="flex flex-col gap-2">
<label class="font-semibold text-on-surface text-sm ml-1" for="instructor">Instructor Name</label>
<input class="w-full bg-surface-container-high border-none focus:ring-2 focus:ring-primary/20 rounded-xl p-4 transition-all outline-none" id="instructor" name="instructor" placeholder="Enter your Instructor name" type="text"/>
<span class="error-msg hidden text-error text-xs ml-1 mt-1">Instructor name cannot be empty.</span>
</div>
<div class="flex flex-col gap-2">
<label class="font-semibold text-on-surface text-sm ml-1" for="credits">Credit Hours</label>
<input class="w-full bg-surface-container-high border-none focus:ring-2 focus:ring-primary/20 rounded-xl p-4 transition-all outline-none" id="credits" max="10" min="1" name="credits" placeholder="" type="number"/>
<span class="error-msg hidden text-error text-xs ml-1 mt-1">Must be between 1 and 10.</span>
</div>
</div>
<div class="flex flex-col gap-2">
<label class="font-semibold text-on-surface text-sm ml-1" for="description">Description</label>
<textarea class="w-full bg-surface-container-high border-none focus:ring-2 focus:ring-primary/20 rounded-xl p-4 transition-all outline-none resize-none" id="description" name="description" placeholder="" rows="5"></textarea>
<span class="error-msg hidden text-error text-xs ml-1 mt-1">Description is necessary for accreditation.</span>
</div>
<div class="flex flex-col md:flex-row items-center justify-between gap-6 pt-4">
<p class="text-on-surface-variant text-sm italic">Fields are validated against the PAF-IAST standard.</p>
<button class="w-full md:w-auto linear-polish text-white px-10 py-4 rounded-full font-bold shadow-xl shadow-blue-500/30 transition-all hover:translate-y-[-2px] active:scale-95 flex items-center gap-2" type="submit">
                                    Add Course <span class="material-symbols-outlined text-lg">arrow_forward</span>
</button>
</div>
</form>
</div>
</div>
</div></div>
</main>
<script src="script.js"></script>
</body></html>
