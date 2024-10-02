<?php 
    include("includes/header.php"); 

    // Ensure the user is signed in before allowing uploads
    if(!$session->is_signed_in()) { 
        redirect("login.php");
    }

    $message = ""; // Initialize the message variable

    // Check if form has been submitted
    if (isset($_POST['submit'])) {
        echo "Form has been submitted!<br>"; // Debug: Check if the form was submitted

        $photo = new Photo();
        $photo->title = $_POST['title'];

        // Debug: Check if the file array is being processed
        if (isset($_FILES['file_upload'])) {
            echo "File upload is detected!<br>";
        } else {
            echo "No file upload detected!<br>";
        }

        // Call set_files to validate and set the file upload details
        $photo->set_files($_FILES['file_upload']);

        // Attempt to save the photo, if successful display success message, else display errors
        if ($photo->save()) {
            $message = "<div class='alert alert-success'>Photo uploaded successfully!</div>";
        } else {
            // Debug: Check if errors are being populated
            echo "Errors detected in the photo upload process!<br>";

            if (!empty($photo->errors)) {
                echo "Errors: " . print_r($photo->errors, true) . "<br>"; // Debugging output
                $message = "<div class='alert alert-danger'>" . join("<br>", $photo->errors) . "</div>";
            } else {
                echo "No specific error found, but the upload failed.<br>";
            }
        }
    } else {
        echo "Form not submitted!<br>"; // Debug: Check if the form was not submitted
    }
?>

<!-- Navigation -->
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <?php include("includes/top_nav.php"); ?>
    <?php include("includes/side_nav.php"); ?>
</nav>

<div id="page-wrapper">
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    Upload Photo
                    <small>Upload your images here</small>
                </h1>

                <div class="col-md-6">
                    <!-- Display success or error messages -->
                    <?php 
                        // Ensure the message is output only if it's not empty
                        if(!empty($message)) {
                            echo $message;
                        } else {
                            echo "No message generated.<br>"; // Debug: Message was not set
                        }
                    ?>

                    <!-- Photo Upload Form -->
                    <form action="upload.php" method="post" enctype="multipart/form-data">
                        
                        <div class="form-group">
                            <label for="title">Photo Title</label>
                            <input type="text" name="title" id="title" required class="form-control" placeholder="Enter photo title">
                        </div>

                        <div class="form-group">
                            <label for="file_upload">Select Photo</label>
                            <input type="file" name="file_upload" id="file_upload" required class="form-control-file">
                        </div>

                        <div class="form-group">
                            <input type="submit" name="submit" value="Upload Photo" class="btn btn-primary">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /.row -->

    </div>
    <!-- /.container-fluid -->
</div>
<!-- /#page-wrapper -->

<?php include("includes/footer.php"); ?>