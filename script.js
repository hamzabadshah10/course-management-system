document.addEventListener('DOMContentLoaded', function() {
    // Basic Form Validation for Add Course
    const addCourseForm = document.getElementById('addCourseForm');
    if (addCourseForm) {
        addCourseForm.addEventListener('submit', function(e) {
            let isValid = true;
            
            // Check course name
            const name = document.getElementById('courseName');
            if (!name.value.trim()) {
                name.nextElementSibling.classList.remove('hidden');
                name.classList.add('ring-error', 'ring-2');
                isValid = false;
            } else {
                name.nextElementSibling.classList.add('hidden');
                name.classList.remove('ring-error', 'ring-2');
            }
            
            // Check course code
            const code = document.getElementById('courseCode');
            if (!code.value.trim()) {
                code.nextElementSibling.classList.remove('hidden');
                code.classList.add('ring-error', 'ring-2');
                isValid = false;
            } else {
                code.nextElementSibling.classList.add('hidden');
                code.classList.remove('ring-error', 'ring-2');
            }

            // Check instructor
            const instructor = document.getElementById('instructor');
            if (!instructor.value.trim()) {
                instructor.nextElementSibling.classList.remove('hidden');
                instructor.classList.add('ring-error', 'ring-2');
                isValid = false;
            } else {
                instructor.nextElementSibling.classList.add('hidden');
                instructor.classList.remove('ring-error', 'ring-2');
            }

            // Check description (JS validation)
            const desc = document.getElementById('description');
            if (!desc.value.trim()) {
                desc.nextElementSibling.classList.remove('hidden');
                desc.classList.add('ring-error', 'ring-2');
                isValid = false;
            } else {
                desc.nextElementSibling.classList.add('hidden');
                desc.classList.remove('ring-error', 'ring-2');
            }

            if (!isValid) {
                e.preventDefault();
                alert("Please correct the errors in the form before submitting.");
            }
        });
    }
});

// Confirmation Box for Deletion
function confirmDelete(id, courseCode) {
    if (confirm("Are you sure you want to delete course " + courseCode + "? This action is permanent.")) {
        window.location.href = "view_courses.php?delete_id=" + id;
    }
}
