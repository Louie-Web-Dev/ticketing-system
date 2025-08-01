<?php
session_start();
$random_num = mt_rand(1000000, 9999999);
$year = date("dmy");

date_default_timezone_set("Asia/Manila");

$date_ = date("Y-m-d h:i:sa");

$ticket_num = $year . "-" .  $random_num;
$name_q = $_SESSION['name'];

if (!isset($_SESSION['username']) || !isset($_SESSION['name'])) {
    header("Location: /TSP-system/ticketing-system/");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toyota IT Ticketing System</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <?php include 'user_nav.php' ?>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
        crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>

<body>


    <div class="Container">
        <div class="content">
            <input type="hidden" id="date_" value="<?php echo $date_; ?>" />
            <input type="hidden" id="ticket_no" value="<?php echo $ticket_num; ?>" />
            <input type="hidden" id="name_" value="<?php echo $_SESSION['name']; ?>" />
            <input type="hidden" id="department" value="<?php echo $_SESSION['department']; ?>" />
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-headset me-2" style="color: white;"></i> Submit IT Concern
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
                                    <i class="fas fa-paper-plane me-2" style="color: white;"></i> Submit Concern
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirm Submit Modal -->
    <div class=" modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Submission</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Proceed with your Concern?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmSubmit">Proceed</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-success">
                <div class="modal-header">
                    <h5 class="modal-title">Success</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Filed Successfully!<br>PLEASE PROCEED TO PENDING PAGE AND CLICK DONE IF THE CONCERN IS FINISHED!
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" onclick="location.reload()">OK</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Error Modal -->
    <div class="modal fade" id="errorModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-danger">
                <div class="modal-header">
                    <h5 class="modal-title">Error</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="errorText"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add this before your own script -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            // Show/hide sub-categories based on category selection
            $('#catgry').on('change', function() {
                var cat = $(this).val();

                // Hide all sub-category sections first
                $('.checkbox-group').hide();

                // Show the relevant section
                if (cat == "PC Software") {
                    $('#pc_soft').show();
                } else if (cat == "PC Hardware") {
                    $('#pc_hard').show();
                } else if (cat == "Internet Connection") {
                    $('#int_conn').show();
                } else if (cat == "Printer") {
                    $('#printer').show();
                } else if (cat == "SAP") {
                    $('#sap').show();
                } else if (cat == "Others") {
                    $('#others').show();
                }

                // Reset all checkboxes
                $('.sub_cat').prop('checked', false);

                // Handle button visibility
                if (cat == "PC Hardware") {
                    $(".proc_btn").show();
                    $(".submit").hide();
                } else {
                    $(".proc_btn").hide();
                    $(".submit").show();
                }
            });

            // Handle sub-category changes for PC Hardware
            $(document).on('change', '.sub_cat', function() {
                if ($('#catgry').val() == "PC Hardware") {
                    var subcat = $(this).val();
                    if (subcat == "Hard Drive" || subcat == "Flash Drive") {
                        $(".proc_btn").hide();
                        $(".submit").show();
                    } else if (subcat == "Mouse" || subcat == "Keyboard" || subcat == "Monitor" || subcat == "UPS") {
                        $(".proc_btn").show();
                        $(".submit").hide();
                    }
                }
            });

            let formDataGlobal = null; // store validated form data globally

            $('#concernForm').on('submit', function(e) {
                e.preventDefault();

                // Validation
                var concern_type = $('#concern_type').val();
                var catgry = $('#catgry').val();
                var desc_ = $('#desc_').val();

                if (!concern_type) return alert("Please select a concern type");
                if (!catgry) return alert("Please select a category");
                if (!desc_) return alert("Please enter a description");

                var selected = [];
                $(".sub_cat:checked").each(function() {
                    selected.push($(this).val());
                });

                if (catgry !== "Others" && selected.length === 0) {
                    return alert("Please check at least one sub-category");
                }

                // Store form data
                formDataGlobal = {
                    'ticket_no': $('#ticket_no').val(),
                    'name_': $('#name_').val(),
                    'department': $('#department').val(),
                    'date_': $('#date_').val(),
                    'catgry': catgry,
                    'selected': selected.join(','),
                    'desc_': desc_,
                    'concern_type': concern_type
                };

                // Show confirmation modal
                var confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
                confirmModal.show();
            });

            // When user clicks "Proceed" in modal
            $('#confirmSubmit').on('click', function() {
                var confirmModalEl = bootstrap.Modal.getInstance(document.getElementById('confirmModal'));
                confirmModalEl.hide();

                $.ajax({
                    url: "insert_concern.php",
                    type: "POST",
                    data: formDataGlobal,
                    success: function(response) {
                        var successModal = new bootstrap.Modal(document.getElementById('successModal'));
                        successModal.show();
                    },
                    error: function(xhr, status, error) {
                        $('#errorText').text("An error occurred: " + error);
                        var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
                        errorModal.show();
                    }
                });
            });

        });
    </script>


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
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);


        }

        :root {
            --primary-blue: #2e2e2eff;
            --secondary-blue: #2e2e2eff;
            --light-gray: #f8f9fa;
            --dark-gray: #343a40;
        }


        body {
            background-color: rgb(221, 221, 221);
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
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

        .required-field {
            color: black;
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
            background-color: #343a40;
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
            background-color: grey;
            transform: translateY(-2px);
            color: white;
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



        @media screen and (max-width: 1555px) and (min-width: 320px) {
            .Container {
                width: 98%;
            }
        }

        @media screen and (max-width: 1950px) and (min-width: 1610px) {
            .Container {
                min-width: 85.4%;
            }
        }
    </style>

</body>

</html>