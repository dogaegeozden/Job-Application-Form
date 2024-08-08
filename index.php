<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Job Application</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" href="./styles.css" />
    </head>

    <body>
        <div id="ServerInfoBox">
            <?php
                function printServerInfo() {
                    echo "<strong>Server Info</strong>";
                    echo "<br>";
                    echo "<br>";
                    echo "File Name: " . $_SERVER['PHP_SELF'];
                    echo "<br>";
                    echo "Host Header: " . $_SERVER['HTTP_HOST'];
                    echo "<br>";
                    echo "Completer URL: " . $_SERVER['HTTP_REFERER'];
                    echo "<br>";
                    echo "User Agent: " . $_SERVER['HTTP_USER_AGENT'];
                    echo "<br>";
                    echo "Script Name: " . $_SERVER['SCRIPT_NAME'];
                    echo "<br>";
                    echo "Version of The Common Gateway Interface: " . $_SERVER['GATEWAY_INTERFACE'];
                    echo "<br>";
                    echo "Server Address: " . $_SERVER['SERVER_ADDR'];
                    echo "<br>";
                    echo "Server Software: " . $_SERVER['SERVER_SOFTWARE'];
                    echo "<br>";
                    echo "Server Protocol: " . $_SERVER['SERVER_PROTOCOL'];
                }

                printServerInfo();
            ?>
        </div>

        <div id="JobApplicationFormBox">
            <?php
                $full_name_err = $email_err = $phone_number_err = $resume_err = $cover_letter_err = "";

                function test_input($data) {
                    $data = trim($data);
                    $data = stripslashes($data);
                    $data = htmlspecialchars($data);
                    return $data;
                }

                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    
                    if (empty($_POST["full-name"])) {
                        $full_name_err = "Name is required";
                    } else {
                        $full_name = test_input($_POST["full-name"]);

                        // check if name only contains letters and whitespace
                        if (!preg_match("/^[a-zA-Z-' ]*$/",$full_name)) {
                            $full_name_err = "Only letters and white space allowed";
                        }
                    }

                    if (empty($_POST["email"])) {
                        $email_err = "Email is required";
                    } else {
                        $email = test_input($_POST["email"]);

                        // check if e-mail address is well-formed
                        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            $email_err = "Invalid email format";
                        }
                    }

                    if (empty($_POST["phone-number"])) {
                        $phone_number_err = "Phone number is required";
                    } else {
                        $phone_number = test_input($_POST["phone-number"]);

                        // check if phone number syntax is valid
                        if (!preg_match("/^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/", $phone_number)) {
                            $phone_number_err = "Invalid phone number";
                        }
                    }

                    $time = test_input(date("Y/m/d"));

                }
            ?>

            <h1>Send Your Application Now!</h1>

            <form id="JobApplicationForm" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" enctype="multipart/form-data">
                <label for="full-name" id="full-name-label">Full Name</label>
                <input type="text" id="full-name" name="full-name" placeholder="Enter your name" required>
                <span class="error">* <?php echo $full_name_err;?></span>

                <label for="email" id="number-label">Email</label>
                <input type="text" id="email" name="email" placeholder="Enter your email address" required>
                <span class="error">* <?php echo $email_err;?></span>

                <label for="phone-number" id="phone-number-label">Phone Number</label>
                <input type="text" id="phone-number" name="phone-number" placeholder="Enter your phone number" required>
                <span class="error">* <?php echo $phone_number_err;?></span>

                <label for="resume" id="resume-label">Resume</label>
                <input type="file" id="resume" name="resume" placeholder="Upload your resume" required>
                <span class="error">* <?php echo $resume_err;?></span>

                <label for="cover_letter" id="cover-letter-label">Cover Letter</label>
                <input type="file" id="cover-letter" name="cover-letter" placeholder="Upload your cover letter"></input>
                <span class="error">* <?php echo $cover_letter_err;?></span>

                <label for="source" id="source-label">Where did you hear about this position?</label>
                <select name="source" id="source">
                    <option value="Linkedin">Linkedin</option>
                    <option value="Glassdoor">Glassdoor</option>
                    <option value="Indeed">Indeed</option>
                </select>
                <span class="error">*<?php echo $cover_letter_err;?></span>

                <button type="submit" name="submitBtn">Submit</button>

            </form>

            <?php

                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    
                    if(isset($_POST['submitBtn'])) {
                        
                        $current_working_directory=getcwd();
                        $target_folder = $current_working_directory . "/" . $full_name;

                        if (!file_exists($target_folder)){

                            mkdir($target_folder, 0777, true);
                            $file_path = $target_folder . "/" . "job_application_" . date("Y_m_d_H_i_s") . ".txt";

                            $file = fopen($file_path, "w");
                            fwrite($file, "Full Name: " . $full_name . "\n" . "Email: " . $email . "\n" . "Phone Number: " . $phone_number . "\n" . $time . "\n");
                            fclose($file);
                            
                            $resumeUploadOk = 1;
                            $coverLetterUploadOk = 1;

                            if ($_FILES["resume"]["name"]) {
                                $target_resume = "$target_folder" . "/" . basename($_FILES["resume"]["name"]);
                                $resumeFileType = pathinfo($target_resume, PATHINFO_EXTENSION);

                                if ($_FILES["resume"]["size"] > 500000) {
                                    echo "<p>Sorry, your file is too large.</p>";
                                    $resumeUploadOk = 0;
                                }

                                if ($resumeFileType != "txt" && $resumeFileType != "pdf" && $resumeFileType != "docx") {
                                    echo "<p>Sorry, only txt, pdf, and docx files are allowed.</p>";
                                    $resumeUploadOk = 0;
                                }

                                if ($resumeUploadOk == 0) {
                                    echo "<p>Sorry, your resume was not uploaded.</p>";
                                } else {
                                    if (move_uploaded_file($_FILES["resume"]["tmp_name"], $target_resume)) {
                                        echo "<p>The file ". basename($_FILES["resume"]["name"]). " has been uploaded.</p>";
                                    } else {
                                        echo "<p>Sorry, there was an error uploading your file.</p>";
                                    }
                                }
                            }

                            if ($_FILES["cover-letter"]["name"]) {
                                $target_cover_letter = $target_folder . "/" . basename($_FILES["cover-letter"]["name"]);
                                $coverLetterFileType = pathinfo($target_cover_letter, PATHINFO_EXTENSION);

                                if ($_FILES["cover-letter"]["size"] > 500000) {
                                    echo "<p>Sorry, your file is too large.</p>";
                                    $coverLetterUploadOk = 0;
                                }
    
                                if ($coverLetterFileType != "txt" && $coverLetterFileType != "pdf" && $coverLetterFileType != "docx") {
                                    echo "<p>Sorry, only txt, pdf, and docx files are allowed.</p>";
                                    $coverLetterUploadOk = 0;
                                }
    
                                if ($coverLetterUploadOk == 0) {
                                    echo "<p>Sorry, your cover letter was not uploaded.</p>";
                                } else {
                                    if (move_uploaded_file($_FILES["cover-letter"]["tmp_name"], $target_cover_letter)) {
                                        echo "The file ". basename($_FILES["cover-letter"]["name"]). " has been uploaded.";
                                    } else {
                                        echo "<p>Sorry, there was an error uploading your file.</p>";
                                    }
                                }
                            }

                            echo "<p style='color: green;'>Your application has been sent. Thank you!</p>";
                            
                        } else {
                            echo "<p>We already received your application.</p>";
                        }
                    }
                }
            ?>

        </div>

    </body>
</html>