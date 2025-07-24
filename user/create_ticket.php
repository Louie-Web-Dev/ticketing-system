<?php
$random_num = mt_rand(1000000, 9999999);
$year = date("dmy");

date_default_timezone_set("Asia/Manila");

$date_ = date("Y-m-d h:i:sa");

$ticket_num = $year . "-" .  $random_num;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IT Help Desk - Submit Concern</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .Container {
            display: flex;
            flex-direction: column;
            background-color: white;
            width: 85.5%;
            height: 91%;
            position: fixed;
            right: 10px;
            margin-top: 83px;
            border: 1px black solid;
            border-radius: 15px;
            padding-bottom: 20px;
        }

        :root {
            --primary-blue: #2163ce;
            --secondary-blue: #1a56b4;
            --light-gray: #f8f9fa;
            --dark-gray: #343a40;
        }

        .row {
            width: 1500px;
        }

        body {
            background-color: #f5f7fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .card-header {
            background-color: var(--primary-blue);
            color: white;
            font-weight: 600;
            padding: 1.2rem;
            border-radius: 0.5rem 0.5rem 0 0 !important;
        }

        .card {
            border: none;
            border-radius: 0.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
            margin-top: 100px;
        }

        .form-section {
            padding: 1.5rem;
            background-color: white;
            border-radius: 0 0 0.5rem 0.5rem;
        }

        .form-label {
            font-weight: 600;
            color: white;
            margin-bottom: 0.5rem;
        }

        .form-control,
        .form-select {
            padding: 0.75rem 1rem;
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
            margin-bottom: 1.25rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 0.25rem rgba(33, 99, 206, 0.25);
        }

        .checkbox-group {
            background-color: black;
            padding: 1rem;
            border-radius: 0.375rem;
            margin-bottom: 1.25rem;
        }

        .checkbox-label {
            margin-right: 1.5rem;
            display: inline-block;
            color: #f8f9fa;
        }

        .checkbox-label .input {
            font-size: 1px;
        }

        .btn-submit {
            background-color: var(--primary-blue);
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 0.375rem;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-submit:hover {
            background-color: var(--secondary-blue);
            transform: translateY(-2px);
        }

        .btn-troubleshoot {
            background-color: #ffc107;
            color: var(--dark-gray);
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 0.375rem;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-troubleshoot:hover {
            background-color: #e0a800;
            transform: translateY(-2px);
        }

        .hidden {
            display: none;
        }

        textarea {
            min-height: 120px;
            resize: vertical;
        }

        .required-field::after {
            content: " *";
            color: #dc3545;
        }
    </style>
</head>

<body>
    <?php include 'user_nav.php' ?>

    <div class="Container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-headset me-2"></i> Submit IT Concern
                    </div>
                    <div class="form-section">
                        <form id="concernForm">
                            <!-- Concern Type -->
                            <div class="mb-4">
                                <label for="concern_type" class="form-label required-field">Type of Concern</label>
                                <select class="form-select" id="concern_type" required>
                                    <option value="" selected disabled>Select Type of Concern</option>
                                    <option value="VIP">VIP</option>
                                    <option value="CLIENT">Client Related</option>
                                    <option value="NON CLIENT">Non Client Related</option>
                                </select>
                            </div>

                            <!-- Category -->
                            <div class="mb-4">
                                <label for="catgry" class="form-label required-field">Category</label>
                                <select id="catgry" class="form-select" required>
                                    <option value="" selected disabled>Select Category</option>
                                    <option value="PC Software">PC Software</option>
                                    <option value="PC Hardware">PC Hardware</option>
                                    <option value="Internet Connection">Internet Connection</option>
                                    <option value="Printer">Printer</option>
                                    <option value="SAP">SAP</option>
                                    <option value="Others">Others</option>
                                </select>
                            </div>

                            <!-- Sub-Categories (initially hidden) -->
                            <!-- PC Software Sub-Categories -->
                            <div class="checkbox-group hidden" id="pc_soft">
                                <label class="form-label">PC Software Issues</label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="checkbox-label">
                                            <input type="checkbox" class="sub_cat" name="sub_cat" value="System"> System
                                        </label>
                                        <label class="checkbox-label">
                                            <input type="checkbox" class="sub_cat" name="sub_cat" value="Operating System"> Operating System
                                        </label>
                                    </div>
                                    <label class="checkbox-label">
                                        <input type="checkbox" class="sub_cat" name="sub_cat" value="MS Office"> MS Office
                                    </label>
                                    <label class="checkbox-label">
                                        <input type="checkbox" class="sub_cat" name="sub_cat" value="Shared Folders"> Shared Folders
                                    </label>
                                </div>
                            </div>

                            <!-- PC Hardware Sub-Categories -->
                            <div class="checkbox-group hidden" id="pc_hard">
                                <label class="form-label">PC Hardware Issues</label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="checkbox-label">
                                            <input type="checkbox" class="sub_cat" name="sub_cat" value="Mouse"> Mouse
                                        </label>
                                        <label class="checkbox-label">
                                            <input type="checkbox" class="sub_cat" name="sub_cat" value="Keyboard"> Keyboard
                                        </label>
                                        <label class="checkbox-label">
                                            <input type="checkbox" class="sub_cat" name="sub_cat" value="Monitor"> Monitor
                                        </label>
                                    </div>
                                    <label class="checkbox-label">
                                        <input type="checkbox" class="sub_cat" name="sub_cat" value="Hard Drive"> Hard Drive
                                    </label>
                                    <label class="checkbox-label">
                                        <input type="checkbox" class="sub_cat" name="sub_cat" value="UPS"> UPS
                                    </label>
                                    <label class="checkbox-label">
                                        <input type="checkbox" class="sub_cat" name="sub_cat" value="Flash Drive"> Flash Drive
                                    </label>
                                    <label class="checkbox-label">
                                        <input type="checkbox" class="sub_cat" name="sub_cat" value="PC Format"> PC Format
                                    </label>
                                </div>
                            </div>

                            <!-- Internet Connection Sub-Categories -->
                            <div class="checkbox-group hidden" id="int_conn">
                                <label class="form-label">Internet Connection Issues</label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="checkbox-label">
                                            <input type="checkbox" class="sub_cat" name="sub_cat" value="Wi-Fi"> Wi-Fi
                                        </label>
                                    </div>
                                    <label class="checkbox-label">
                                        <input type="checkbox" class="sub_cat" name="sub_cat" value="LAN"> LAN
                                    </label>
                                </div>
                            </div>

                            <!-- Printer Sub-Categories -->
                            <div class="checkbox-group hidden" id="printer">
                                <label class="form-label">Printer Issues</label>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="checkbox-label">
                                            <input type="checkbox" class="sub_cat" name="sub_cat" value="Print"> Print
                                        </label>


                                        <label class="checkbox-label">
                                            <input type="checkbox" class="sub_cat" name="sub_cat" value="Photocopy"> Photocopy
                                        </label>


                                        <label class="checkbox-label">
                                            <input type="checkbox" class="sub_cat" name="sub_cat" value="Scan"> Scan
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- SAP Sub-Categories -->
                            <div class="checkbox-group hidden" id="sap">
                                <label class="form-label">SAP Issues</label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="checkbox-label">
                                            <input type="checkbox" class="sub_cat" name="sub_cat" value="Lock/Unlock of Account"> Lock/Unlock of Account
                                        </label>
                                        <label class="checkbox-label">
                                            <input type="checkbox" class="sub_cat" name="sub_cat" value="Changing Password of Account"> Change Password of Account
                                        </label>


                                        <label class="checkbox-label">
                                            <input type="checkbox" class="sub_cat" name="sub_cat" value="Addition of access roles"> Addition of access roles
                                        </label>
                                        <label class="checkbox-label">
                                            <input type="checkbox" class="sub_cat" name="sub_cat" value="Others"> Others
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Others Sub-Categories -->
                            <div class="checkbox-group hidden" id="others">
                                <label class="form-label">Other IT Issues</label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="checkbox-label">
                                            <input type="checkbox" class="sub_cat" name="sub_cat" value="tvcon"> TV Con
                                        </label>
                                        <label class="checkbox-label">
                                            <input type="checkbox" class="sub_cat" name="sub_cat" value="proj"> Projector Setup
                                        </label>
                                        <label class="checkbox-label">
                                            <input type="checkbox" class="sub_cat" name="sub_cat" value="pcrel"> PC Relocation
                                        </label>
                                        <label class="checkbox-label">
                                            <input type="checkbox" class="sub_cat" name="sub_cat" value="Others"> Others
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="mb-4">
                                <label for="desc_" class="form-label required-field">Description</label>
                                <textarea id="desc_" class="form-control" placeholder="Please describe your issue in detail..." required></textarea>
                                <div class="form-text">Be as specific as possible to help us resolve your issue faster.</div>
                            </div>

                            <!-- Buttons -->
                            <div class="d-grid gap-3 d-md-flex justify-content-md-end">
                                <button type="button" class="btn btn-troubleshoot hidden" id="proc_btn">
                                    <i class="fas fa-wrench me-2"></i> Troubleshooting Guide
                                </button>
                                <button type="submit" class="btn btn-submit" id="submit">
                                    <i class="fas fa-paper-plane me-2"></i> Submit Concern
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Show/hide sub-categories based on category selection
        document.getElementById('catgry').addEventListener('change', function() {
            // Hide all sub-category sections first
            document.querySelectorAll('.checkbox-group').forEach(el => {
                el.classList.add('hidden');
            });

            // Show the selected category's sub-categories
            const selectedCategory = this.value;
            if (selectedCategory === 'PC Software') {
                document.getElementById('pc_soft').classList.remove('hidden');
            } else if (selectedCategory === 'PC Hardware') {
                document.getElementById('pc_hard').classList.remove('hidden');
            } else if (selectedCategory === 'Internet Connection') {
                document.getElementById('int_conn').classList.remove('hidden');
            } else if (selectedCategory === 'Printer') {
                document.getElementById('printer').classList.remove('hidden');
            } else if (selectedCategory === 'SAP') {
                document.getElementById('sap').classList.remove('hidden');
            } else if (selectedCategory === 'Others') {
                document.getElementById('others').classList.remove('hidden');
            }

            // Show/hide troubleshooting button based on category
            const troubleshootBtn = document.getElementById('proc_btn');
            if (selectedCategory === 'PC Software' || selectedCategory === 'PC Hardware' ||
                selectedCategory === 'Internet Connection' || selectedCategory === 'Printer') {
                troubleshootBtn.classList.remove('hidden');
            } else {
                troubleshootBtn.classList.add('hidden');
            }
        });

        // Form submission
        document.getElementById('concernForm').addEventListener('submit', function(e) {
            e.preventDefault();
            // Here you would typically gather the form data and send it to the server
            alert('Concern submitted successfully!');
            this.reset();
            document.querySelectorAll('.checkbox-group').forEach(el => {
                el.classList.add('hidden');
            });
            document.getElementById('proc_btn').classList.add('hidden');
        });

        $('#submit').click(function() {

            var conf = confirm("Proceed with your Concern?");
            if (conf == true) {

                var catgry = document.getElementById("catgry").value;

                /* declare an checkbox array */
                var chkArray = [];

                /* look for all checkboes that have a class 'chk' attached to it and check if it was checked */
                $("#sub_cat:checked").each(function() {
                    chkArray.push($(this).val());

                });

                /* we join the array separated by the comma */
                var selected;
                selected = chkArray.join(',');

                /* check if there is selected checkboxes, by default the length is 1 as it contains one single comma */
                if (selected.length > 0) {

                    var date_ = document.getElementById("date_").value;
                    var ticket_no = document.getElementById("ticket_no").value;
                    var name_ = document.getElementById("name_").value;
                    var department = document.getElementById("department").value;
                    var catgry = document.getElementById("catgry").value;
                    var desc_ = document.getElementById("desc_").value;
                    var concern_type = document.getElementById("concern_type").value;

                    //alert(selected)
                    //alert(catgry + desc + selected);

                    $.ajax({
                        url: "insert_concern.php",
                        type: "POST",
                        data: {
                            'ticket_no': ticket_no,
                            'name_': name_,
                            'department': department,
                            'date_': date_,
                            'catgry': catgry,
                            'selected': selected,
                            'desc_': desc_,
                            'concern_type': concern_type
                        },
                        success: function(data) {

                            alert("Filed Successfully!" + "\n" + "PLEASE PROCEED TO PENDING PAGE AND CLICK DONE IF THE CONCERN IS FINISHED!");
                            location.reload(true);


                        }
                    });


                } else {
                    alert("Please check at least one sub-category");
                }

            } //end if ng confirm

        });
    </script>
</body>

</html>