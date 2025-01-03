<div class="container">
    <div class="row gap-5">
        <!-- Form section -->
        <section class="col-md-3">
            <h2 class="text-center mb-4">How many children to be gifted?</h2>
            <div class="card rounded-3 shadow-lg">
                <form action="" class="py-5">
                    <div class="container mt-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="number" class="form-control" id="girl" placeholder="girl" name="girl">
                                    <label for="girl">Girl</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="number" class="form-control" id="boy" placeholder="boy" name="boy">
                                    <label for="boy">Boy</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-3">
                        <button class="btn btn-primary w-75" id="view-gifts">View gifts</button>
                    </div>
                </form>
            </div>

            <div class="container-fluid mt-2">
                <div class="row text-center">
                    <div class="col-md-5">
                        <button class="btn btn-success w-100">50$</button>
                    </div>
                    <div class="col-md-7">
                        <button class="btn btn-danger w-100">Validate</button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Gift listing -->
        <section class="col-md-8 overflow-auto ml-5" id="gift-list" style="max-height: 500px;">
            <h2 class="text-center mb-4">Gift Suggestions</h2>
            <div class="text-center my-3" id="loading" style="display: none;">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
            <div class="row">
                <!-- Placeholder content for the second section (initial view before JS loads actual gifts) -->
                <div class="col-md-12 text-center">
                    <div class="placeholder-placeholder">
                        <p><strong>Choose the number of children to receive gifts, and click "View gifts" to see
                                suggestions.</strong></p>
                        <p>Once the gifts are displayed, you can select and replace gifts accordingly.</p>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>