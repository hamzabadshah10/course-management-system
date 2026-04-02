import os
import re

def process_html_to_php():
    # Read files
    for file, out in [('home.html', 'index.php'), ('add_course.html', 'add_course.php'), ('view_courses.html', 'view_courses.php')]:
        if not os.path.exists(file): 
            print(f"File not found: {file}")
            continue
        print(f"Processing {file} to {out}")
        with open(file, 'r', encoding='utf-8') as f:
            content = f.read()
        
        # Replace href="#" with actual links
        content = re.sub(r'<a[^>]+>(?:<span[^>]*>.*?</span>\s*)?Home\s*</a>', lambda m: m.group(0).replace('href="#"', 'href="index.php"'), content)
        content = re.sub(r'<a[^>]+>(?:<span[^>]*>.*?</span>\s*)?[A-Za-z\s]*Add Course\s*</a>', lambda m: m.group(0).replace('href="#"', 'href="add_course.php"'), content)
        content = re.sub(r'<a[^>]+>(?:<span[^>]*>.*?</span>\s*)?[A-Za-z\s]*View Courses\s*</a>', lambda m: m.group(0).replace('href="#"', 'href="view_courses.php"'), content)
        
        # Add JS link
        content = content.replace('</body>', '  <script src="script.js"></script>\n</body>')
        
        if file == 'add_course.html':
            # Add PHP form handling
            php_code = '''<?php
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
'''
            content = php_code + content
            # update form tag
            content = content.replace('<form class="space-y-8" id="addCourseForm" novalidate="">', '<form class="space-y-8" id="addCourseForm" novalidate="" method="POST" action="add_course.php">')
            # insert message
            content = content.replace('<div class="hidden p-4 rounded-xl text-sm flex items-center gap-3" id="formStatus"></div>', '<?php echo $message; ?>\n                                <div class="hidden p-4 rounded-xl text-sm flex items-center gap-3" id="formStatus"></div>')

        elif file == 'view_courses.html':
            php_code = '''<?php
require_once "db.php";
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $conn->query("DELETE FROM courses WHERE id = " . intval($id));
    header("Location: view_courses.php");
    exit();
}
$result = $conn->query("SELECT * FROM courses ORDER BY created_at DESC");
?>
'''
            content = php_code + content
            
            # Find the tbody and replace its content with php loop
            tbody_start = content.find('<tbody>')
            if tbody_start == -1: tbody_start = content.find('<tbody')
            
            if tbody_start != -1:
                tbody_end = content.find('</tbody>', tbody_start)
                
                php_loop = '''<tbody class="divide-y divide-surface-container">
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
                                                <span class="material-symbols-outlined text-gray-500">person</span>
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
                                    <tr><td colspan="5" class="px-8 py-6 text-center text-on-surface-variant">No courses found in the system.</td></tr>
                                <?php endif; ?>
'''
                
                # replace tbody content
                content = content[:content.find('>', tbody_start)+1] + "\n" + php_loop + content[tbody_end:]
                
                # update pagination
                content = re.sub(r'<span>Showing.*active courses</span>', '<span>Showing <?php echo $result ? $result->num_rows : 0; ?> active courses</span>', content)
            
        with open(out, 'w', encoding='utf-8') as f:
            f.write(content)
        print(f"Created {out}")

if __name__ == '__main__':
    process_html_to_php()
