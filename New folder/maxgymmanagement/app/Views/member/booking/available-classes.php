<?= $this->extend('templates/member_layout') ?>
<?= $this->section('content') ?>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title">Available Classes</h4>
                        <p class="card-category">Book your fitness classes</p>
                    </div>
                    <div class="card-body">

                        <!-- Date Filter -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="dateFilter">Filter by Date</label>
                                    <input 
                                        type="date" 
                                        class="form-control" 
                                        id="dateFilter" 
                                        value="<?= esc($selectedDate ?? date('Y-m-d')) ?>" 
                                        min="<?= date('Y-m-d') ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>&nbsp;</label>
                                <div>
                                    <button class="btn btn-primary" onclick="filterByDate()">Filter</button>
                                    <a href="<?= base_url('member/booking/dashboard?member_id=' . $member['id_member']) ?>" class="btn btn-secondary">
                                        Back to Dashboard
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Membership Info -->
                        <div class="alert alert-info">
                            <strong>Membership Status:</strong> <?= ucfirst(esc($member['status_membership'])) ?>
                            <?php if (!empty($member['nama_package'])): ?>
                                | <strong>Package:</strong> <?= esc($member['nama_package']) ?>
                                <?php if (!empty($member['unlimited_classes'])): ?>
                                    | <strong>Access:</strong> Unlimited Classes
                                <?php else: ?>
                                    | <strong>Remaining Sessions:</strong> <?= esc($member['sisa_pt_sessions'] ?? 0) ?>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>

                        <!-- Available Classes -->
                        <?php if (empty($schedules)): ?>
                            <div class="text-center">
                                <h5 class="info-title">No classes available</h5>
                                <p class="card-category">There are no available classes for the selected date.</p>
                            </div>
                        <?php else: ?>
                            <div class="row">
                                <?php foreach ($schedules as $schedule): ?>
                                    <div class="col-md-6 col-lg-4 mb-4">
                                        <div class="card h-100">
                                            <div class="card-header">
                                                <h5 class="card-title mb-0"><?= esc($schedule['nama_class']) ?></h5>
                                            </div>
                                            <div class="card-body">
                                                <div><strong>Date:</strong> <?= date('d M Y', strtotime($schedule['tanggal'])) ?></div>
                                                <div><strong>Time:</strong> <?= date('H:i', strtotime($schedule['waktu_mulai'])) ?> - <?= date('H:i', strtotime($schedule['waktu_selesai'])) ?></div>
                                                <div><strong>Instructor:</strong> <?= esc($schedule['instructor'] ?? 'TBA') ?></div>
                                                <div><strong>Location:</strong> <?= esc($schedule['lokasi'] ?? 'Main Studio') ?></div>
                                                <div><strong>Available Spots:</strong>
                                                    <span class="badge badge-<?= ($schedule['available_capacity'] > 0 ? 'success' : 'danger') ?>">
                                                        <?= esc($schedule['available_capacity']) ?>/<?= esc($schedule['kapasitas']) ?>
                                                    </span>
                                                </div>

                                                <?php if (!empty($schedule['deskripsi'])): ?>
                                                    <div class="mt-2">
                                                        <small class="text-muted"><?= esc($schedule['deskripsi']) ?></small>
                                                    </div>
                                                <?php endif; ?>

                                                <?php if (!empty($schedule['can_book'])): ?>
                                                    <button class="btn btn-primary btn-block book-class"
                                                            data-schedule-id="<?= esc($schedule['id_schedule']) ?>"
                                                            data-member-id="<?= esc($member['id_member']) ?>"
                                                            data-class-name="<?= esc($schedule['nama_class']) ?>">
                                                        <i class="material-icons">book_online</i> Book Class
                                                    </button>
                                                <?php else: ?>
                                                    <button class="btn btn-secondary btn-block" disabled>
                                                        <i class="material-icons">block</i> <?= esc($schedule['booking_reason'] ?? 'Not Available') ?>
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Booking Confirmation Modal -->
<div class="modal fade" id="bookingModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Booking</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to book <strong id="modalClassName"></strong>?</p>
                <div class="alert alert-info">
                    <small>
                        - You can cancel your booking up to 2 hours before the class starts.<br>
                        - Late cancellations may affect your membership sessions.
                    </small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmBooking">Confirm Booking</button>
            </div>
        </div>
    </div>
</div>

<script>
$(function() {
    let selectedScheduleId = null;
    let selectedMemberId = null;

    // Book class button
    $('.book-class').on('click', function() {
        selectedScheduleId = $(this).data('schedule-id');
        selectedMemberId = $(this).data('member-id');
        const className = $(this).data('class-name');

        $('#modalClassName').text(className);
        $('#bookingModal').modal('show');
    });

    // Confirm booking AJAX
    $('#confirmBooking').on('click', function() {
        if (!selectedScheduleId || !selectedMemberId) return;

        $.ajax({
            url: '<?= base_url("member/booking/book") ?>',
            method: 'POST',
            dataType: 'json',
            data: {
                schedule_id: selectedScheduleId,
                member_id: selectedMemberId,
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            },
            success: function(response) {
                $('#bookingModal').modal('hide');

                if (response.success) {
                    alert('✅ Class booked successfully!');
                    location.reload();
                } else {
                    alert('⚠️ ' + (response.message || 'Booking failed.'));
                }
            },
            error: function(xhr, status, error) {
                $('#bookingModal').modal('hide');
                alert('❌ An error occurred while booking the class: ' + error);
            }
        });
    });

    // Filter by date
    window.filterByDate = function() {
        const date = $('#dateFilter').val();
        if (date) {
            const url = '<?= base_url("member/booking/available-classes") ?>' + 
                        '?member_id=<?= $member['id_member'] ?>&date=' + date;
            window.location.href = url;
        }
    };
});
</script>

<?= $this->endSection() ?>
