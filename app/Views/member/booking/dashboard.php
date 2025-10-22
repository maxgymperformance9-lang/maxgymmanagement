<?= $this->extend('templates/member_layout') ?>
<?= $this->section('content') ?>
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title">My Class Bookings</h4>
                        <p class="card-category">Welcome back, <?= esc($member['nama_member']) ?>!</p>
                    </div>
                    <div class="card-body">
                        <!-- Booking Statistics -->
                        <div class="row">
                            <div class="col-md-3">
                                <div class="card card-stats">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-5">
                                                <div class="icon-big text-center icon-warning">
                                                    <i class="material-icons">event_available</i>
                                                </div>
                                            </div>
                                            <div class="col-7">
                                                <div class="numbers">
                                                    <p class="card-category">Upcoming</p>
                                                    <h4 class="card-title"><?= $bookingStats['upcoming_bookings'] ?? 0 ?></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card card-stats">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-5">
                                                <div class="icon-big text-center icon-success">
                                                    <i class="material-icons">check_circle</i>
                                                </div>
                                            </div>
                                            <div class="col-7">
                                                <div class="numbers">
                                                    <p class="card-category">Completed</p>
                                                    <h4 class="card-title"><?= $bookingStats['completed_sessions'] ?? 0 ?></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card card-stats">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-5">
                                                <div class="icon-big text-center icon-info">
                                                    <i class="material-icons">fitness_center</i>
                                                </div>
                                            </div>
                                            <div class="col-7">
                                                <div class="numbers">
                                                    <p class="card-category">Remaining Sessions</p>
                                                    <h4 class="card-title"><?= $bookingStats['remaining_sessions'] ?? 0 ?></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card card-stats">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-5">
                                                <div class="icon-big text-center icon-primary">
                                                    <i class="material-icons">book_online</i>
                                                </div>
                                            </div>
                                            <div class="col-7">
                                                <div class="numbers">
                                                    <p class="card-category">Total Bookings</p>
                                                    <h4 class="card-title"><?= $bookingStats['total_bookings'] ?? 0 ?></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <a href="<?= base_url('member/booking/available-classes?member_id=' . $member['id_member']) ?>" class="btn btn-primary">
                                    <i class="material-icons">add</i> Book New Class
                                </a>
                                <a href="<?= base_url('member/classes/schedule?member_id=' . $member['id_member']) ?>" class="btn btn-secondary">
                                    <i class="material-icons">schedule</i> View Class Schedule
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upcoming Bookings -->
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="card">
                    <div class="card-header card-header-info">
                        <h4 class="card-title">Upcoming Bookings</h4>
                    </div>
                    <div class="card-body">
                        <?php if (empty($upcomingBookings)): ?>
                            <div class="text-center">
                                <h5 class="info-title">No upcoming bookings</h5>
                                <p class="card-category">You haven't booked any classes yet.</p>
                                <a href="<?= base_url('member/booking/available-classes?member_id=' . $member['id_member']) ?>" class="btn btn-primary">
                                    Browse Available Classes
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="text-primary">
                                        <th>Class Name</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Instructor</th>
                                        <th>Location</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($upcomingBookings as $booking): ?>
                                            <tr>
                                                <td><?= esc($booking['nama_class']) ?></td>
                                                <td><?= date('d M Y', strtotime($booking['tanggal'])) ?></td>
                                                <td><?= date('H:i', strtotime($booking['waktu_mulai'])) ?> - <?= date('H:i', strtotime($booking['waktu_selesai'])) ?></td>
                                                <td><?= esc($booking['instructor'] ?? '-') ?></td>
                                                <td><?= esc($booking['lokasi'] ?? '-') ?></td>
                                                <td>
                                                    <span class="badge badge-info">Booked</span>
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-danger cancel-booking" data-booking-id="<?= $booking['id_booking'] ?>" data-member-id="<?= $member['id_member'] ?>">
                                                        <i class="material-icons">cancel</i> Cancel
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Booking History -->
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="card">
                    <div class="card-header card-header-success">
                        <h4 class="card-title">Booking History</h4>
                    </div>
                    <div class="card-body">
                        <?php if (empty($bookingHistory)): ?>
                            <div class="text-center">
                                <h5 class="info-title">No booking history</h5>
                                <p class="card-category">Your completed classes will appear here.</p>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="text-primary">
                                        <th>Class Name</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Instructor</th>
                                        <th>Location</th>
                                        <th>Status</th>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($bookingHistory as $booking): ?>
                                            <tr>
                                                <td><?= esc($booking['nama_class']) ?></td>
                                                <td><?= date('d M Y', strtotime($booking['tanggal'])) ?></td>
                                                <td><?= date('H:i', strtotime($booking['waktu_mulai'])) ?> - <?= date('H:i', strtotime($booking['waktu_selesai'])) ?></td>
                                                <td><?= esc($booking['instructor'] ?? '-') ?></td>
                                                <td><?= esc($booking['lokasi'] ?? '-') ?></td>
                                                <td>
                                                    <?php
                                                    $statusClass = 'secondary';
                                                    $statusText = 'Unknown';
                                                    switch ($booking['status']) {
                                                        case 'attended':
                                                            $statusClass = 'success';
                                                            $statusText = 'Attended';
                                                            break;
                                                        case 'cancelled':
                                                            $statusClass = 'danger';
                                                            $statusText = 'Cancelled';
                                                            break;
                                                        case 'no_show':
                                                            $statusClass = 'warning';
                                                            $statusText = 'No Show';
                                                            break;
                                                    }
                                                    ?>
                                                    <span class="badge badge-<?= $statusClass ?>"><?= $statusText ?></span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Cancel booking
    $('.cancel-booking').on('click', function() {
        const bookingId = $(this).data('booking-id');
        const memberId = $(this).data('member-id');

        if (confirm('Are you sure you want to cancel this booking?')) {
            $.ajax({
                url: '<?= base_url("member/booking/cancel") ?>',
                method: 'POST',
                data: {
                    booking_id: bookingId,
                    member_id: memberId,
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                },
                success: function(response) {
                    if (response.success) {
                        alert('Booking cancelled successfully');
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function() {
                    alert('An error occurred while cancelling the booking');
                }
            });
        }
    });
});
</script>
<?= $this->endSection() ?>
