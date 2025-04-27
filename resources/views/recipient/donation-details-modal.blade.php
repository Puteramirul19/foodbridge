{{-- Donation Details Modal --}}

<div class="modal fade" id="donationDetailsModal" tabindex="-1"> <div class="modal-dialog modal-lg"> <div class="modal-content"> <div class="modal-header"> <h5 class="modal-title">Donation Details</h5> <button type="button" class="btn-close" data-bs-dismiss="modal"></button> </div> <div class="modal-body"> <div class="row"> <div class="col-md-8"> <div class="card border-0 mb-3"> <div class="card-header bg-light"> <h4 class="mb-0"> <i class="fas fa-utensils me-2"></i>Food Donation Information </h4> </div> <div class="card-body"> <div class="row"> <div class="col-md-6"> <h5 class="text-primary">Food Details</h5> <table class="table table-borderless"> <tr> <th>Description</th> <td id="modal-food-description"></td> </tr> <tr> <th>Category</th> <td id="modal-food-category"></td> </tr> <tr> <th>Estimated Servings</th> <td id="modal-estimated-servings"></td> </tr> <tr> <th>Best Before</th> <td id="modal-best-before"></td> </tr> </table> </div> <div class="col-md-6"> <h5 class="text-primary">Pickup Details</h5> <table class="table table-borderless"> <tr> <th>Donation Type</th> <td id="modal-donation-type"></td> </tr> <tr> <th>Pickup Location</th> <td id="modal-pickup-location"></td> </tr> <tr> <th>Contact Number</th> <td id="modal-contact-number"></td> </tr> </table> </div> </div> <div id="modal-additional-instructions"> <h5 class="text-primary mt-3">Additional Instructions</h5> <p id="modal-instructions-text"></p> </div> </div> </div> </div> <div class="col-md-4"> <div class="card border-0"> <div class="card-header bg-light"> <h4 class="mb-0"> <i class="fas fa-user me-2"></i>Donor Information </h4> </div> <div class="card-body"> <div class="text-center mb-3"> <img id="modal-donor-avatar" src="{{ asset('images/default-avatar.png') }}" class="rounded-circle mb-2" style="width: 100px; height: 100px; object-fit: cover;"> <h5 id="modal-donor-name" class="mb-1"></h5> <p id="modal-donor-role" class="text-muted"></p> </div>
                            <h6 class="text-primary">Contact Details</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <th><i class="fas fa-envelope me-2"></i>Email</th>
                                    <td id="modal-donor-email"></td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-phone me-2"></i>Phone</th>
                                    <td id="modal-donor-phone"></td>
                                </tr>
                            </table>

                            <h6 class="text-primary mt-3">Donor Bio</h6>
                            <p id="modal-donor-bio" class="text-muted"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" id="accept-donation-btn">
                <i class="fas fa-shopping-basket me-2"></i>Accept Donation
            </button>
        </div>
    </div>
</div>
</div>
{{-- Pickup Request Modal --}}

<div class="modal fade" id="pickupRequestModal" tabindex="-1"> <div class="modal-dialog"> <div class="modal-content"> <div class="modal-header"> <h5 class="modal-title">Accept Donation</h5> <button type="button" class="btn-close" data-bs-dismiss="modal"></button> </div> <form id="pickupRequestForm" method="POST"> @csrf <div class="modal-body"> <div class="mb-3"> <label class="form-label">Pickup Date</label> <input type="date" name="pickup_date" class="form-control" required min="{{ now()->format('Y-m-d') }}"> </div> <div class="mb-3"> <label class="form-label">Pickup Time</label> <input type="time" name="pickup_time" class="form-control" required> </div> <div class="mb-3"> <label class="form-label">Additional Notes (Optional)</label> <textarea name="notes" class="form-control" rows="3" placeholder="Any special instructions or requirements"></textarea> </div> </div> <div class="modal-footer"> <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button> <button type="submit" class="btn btn-primary">Confirm Acceptance</button> </div> </form> </div> </div> </div>
@section('scripts')

<script>
@section('scripts')

<script> document.addEventListener('DOMContentLoaded', function() { const donationDetailsModal = new bootstrap.Modal(document.getElementById('donationDetailsModal')); const pickupRequestModal = new bootstrap.Modal(document.getElementById('pickupRequestModal')); const viewButtons = document.querySelectorAll('.view-donation-btn'); const pickupRequestForm = document.getElementById('pickupRequestForm'); const acceptDonationBtn = document.getElementById('accept-donation-btn'); let currentDonationId = null; viewButtons.forEach(button => { button.addEventListener('click', function() { const donationData = JSON.parse(this.dataset.donationDetails); // Populate Donation Details document.getElementById('modal-food-description').textContent = donationData.food_description; document.getElementById('modal-food-category').textContent = donationData.food_category; document.getElementById('modal-estimated-servings').textContent = donationData.estimated_servings; document.getElementById('modal-best-before').textContent = donationData.best_before; document.getElementById('modal-donation-type').textContent = donationData.donation_type; document.getElementById('modal-pickup-location').textContent = donationData.pickup_location; document.getElementById('modal-contact-number').textContent = donationData.contact_number; // Additional Instructions const additionalInstructionsEl = document.getElementById('modal-additional-instructions'); const instructionsTextEl = document.getElementById('modal-instructions-text'); if (donationData.additional_instructions) { additionalInstructionsEl.style.display = 'block'; instructionsTextEl.textContent = donationData.additional_instructions; } else { additionalInstructionsEl.style.display = 'none'; } // Donor Details document.getElementById('modal-donor-name').textContent = donationData.donor.name; document.getElementById('modal-donor-role').textContent = donationData.donor.role; document.getElementById('modal-donor-email').textContent = donationData.donor.email; document.getElementById('modal-donor-phone').textContent = donationData.donor.phone; // Donor Avatar (use default if not provided) const donorAvatarEl = document.getElementById('modal-donor-avatar'); donorAvatarEl.src = donationData.donor.avatar || "{{ asset('images/default-avatar.png') }}"; // Donor Bio const donorBioEl = document.getElementById('modal-donor-bio'); donorBioEl.textContent = donationData.donor.bio || 'No bio available'; // Set current donation ID for pickup request currentDonationId = donationData.id; donationDetailsModal.show(); }); }); // Accept Donation Button acceptDonationBtn.addEventListener('click', function() { donationDetailsModal.hide(); setTimeout(() => { pickupRequestForm.action = `/recipient/donations/${currentDonationId}/reserve`; pickupRequestModal.show(); }, 300); }); }); </script>
@endsection

