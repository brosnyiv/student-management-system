
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Registration Portal</title>
    <link rel="stylesheet" href="staff register.css">
    
    <style>
    

    </style>

</head>
<body>
    <header>
        <div class="container header-content">
            <div class="logo-address">
                <div class="logo">School Staff Portal</div>
                <div class="address">123 Education Avenue, Learning City, ED 12345</div>
            </div>
            <div class="school-logo">
                <img src="logo.png" alt="School Logo"/>
            </div>
        </div>
    </header>
    
    <!-- Staff Type Selection -->
    <div class="container" id="staffTypeContainer">
        <div class="form-container">
            <h1>Staff Registration</h1>
            <div class="staff-type-selection">
                <h2>Please select your staff type:</h2>
                <div class="staff-type-options"  >
                    <div class="staff-type-card" id="teachingStaff" onclick="window.location.href='teaching.php'">
                        <div class="staff-icon">👨‍🏫</div>
                        <h3>Teaching Staff</h3>
                        <p>For teachers, lecturers, professors and other academic staff</p>
                    </div>
                    <div class="staff-type-card" id="nonTeachingStaff"onclick="window.location.href='non-teaching.php'">
                        <div class="staff-icon">👩‍💼</div>
                        <h3>Non-Teaching Staff</h3>
                        <p>For administrative, support, maintenance and other non-academic staff</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <script>
        
        document.addEventListener('DOMContentLoaded', function() {
    // Staff Type Selection
    const teachingStaffCard = document.getElementById('teachingStaff');
    const nonTeachingStaffCard = document.getElementById('nonTeachingStaff')});
    
</script>
</body>
</html>