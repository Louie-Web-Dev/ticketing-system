<?php
session_start();
$random_num = mt_rand(1000000, 9999999);
$year = date("dmy");

date_default_timezone_set("Asia/Manila");

$date_ = date("Y-m-d h:i:sa");

$ticket_num = $year . "-" .  $random_num;
$name_q = $_SESSION['name'];

if (!isset($_SESSION['username']) || !isset($_SESSION['name'])) {
    header("Location: login.php");
    exit();
}

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
    <link rel="stylesheet" href="create_ticket.css">
</head>

<body>
    <?php include 'user_nav.php' ?>

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

            // Form submission handler
            $('#concernForm').on('submit', function(e) {
                e.preventDefault();

                var conf = confirm("Proceed with your Concern?");
                if (!conf) return false;

                // Basic form validation
                var concern_type = $('#concern_type').val();
                var catgry = $('#catgry').val();
                var desc_ = $('#desc_').val();

                if (!concern_type) {
                    alert("Please select a concern type");
                    return false;
                }

                if (!catgry) {
                    alert("Please select a category");
                    return false;
                }

                if (!desc_) {
                    alert("Please enter a description");
                    return false;
                }

                // Checkbox validation
                var selected = [];
                $(".sub_cat:checked").each(function() {
                    selected.push($(this).val());
                });

                // For categories that have sub-categories, require at least one selection
                if (catgry !== "Others" && selected.length === 0) {
                    alert("Please check at least one sub-category");
                    return false;
                }

                // Prepare data
                var formData = {
                    'ticket_no': $('#ticket_no').val(),
                    'name_': $('#name_').val(),
                    'department': $('#department').val(),
                    'date_': $('#date_').val(),
                    'catgry': catgry,
                    'selected': selected.join(','),
                    'desc_': desc_,
                    'concern_type': concern_type
                };

                // Submit via AJAX
                $.ajax({
                    url: "insert_concern.php",
                    type: "POST",
                    data: formData,
                    success: function(response) {
                        alert("Filed Successfully!\nPLEASE PROCEED TO PENDING PAGE AND CLICK DONE IF THE CONCERN IS FINISHED!");
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        alert("An error occurred: " + error);
                    }
                });
            });
        });
    </script>
</body>

</html>