<?php
while ($row = mysqli_fetch_assoc($query)) {
    $sql2 = "SELECT * FROM messages 
             WHERE (incoming_msg_id = {$row['id']} OR outgoing_msg_id = {$row['id']}) 
               AND (outgoing_msg_id = {$outgoing_id} OR incoming_msg_id = {$outgoing_id}) 
             ORDER BY msg_id DESC LIMIT 1";

    $query2 = mysqli_query($conn, $sql2);
    $row2 = mysqli_fetch_assoc($query2);

    $result = (mysqli_num_rows($query2) > 0) ? $row2['msg'] : "No message available";
    $msg = (strlen($result) > 28) ? substr($result, 0, 28) . '...' : $result;

    if (isset($row2['outgoing_msg_id'])) {
        $you = ($outgoing_id == $row2['outgoing_msg_id']) ? "You: " : "";
    } else {
        $you = "";
    }

    $offline = ($row['status'] == "Offline now") ? "offline" : "";
    $hid_me = ($outgoing_id == $row['id']) ? "hide" : "";

    $img = (!empty($row['img'])) ? $row['img'] : 'default.jpg';

    $output .= '<a href="chat.php?user_id=' . $row['id'] . '">
                    <div class="content">
                        <img src="php/images/' . $img . '" alt="">
                        <div class="details">
                            <span>' . $row['name'] . '</span>
                            <p>' . $you . $msg . '</p>
                        </div>
                    </div>
                    <div class="status-dot ' . $offline . '"><i class="fas fa-circle"></i></div>
                </a>';
}
