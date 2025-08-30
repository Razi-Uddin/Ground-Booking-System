<?php
include 'config/db.php';
?>
<?php include 'includes/header.php'; ?>

<h1 class="text-center my-4">Available Grounds</h1>
<div class="container">
    <div class="row">
        <?php
        $sql = "SELECT * FROM grounds WHERE status = 1";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($ground = $result->fetch_assoc()) {
                echo '
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="assets/images/grounds/'.$ground['image'].'" class="card-img-top" alt="'.$ground['name'].'" style="height:200px;object-fit:cover;">
                        <div class="card-body">
                            <h5 class="card-title">'.$ground['name'].'</h5>
                            <p><strong>Location:</strong> '.$ground['location'].'</p>
                            <p><strong>Per Hour:</strong> Rs '.$ground['per_hour_charge'].'</p>
                            <a href="booking.php?ground_id='.$ground['id'].'" class="btn btn-primary">Book Now</a>
                        </div>
                    </div>
                </div>';
            }
        } else {
            echo "<p class='text-center'>No grounds available at the moment.</p>";
        }
        ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
