    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow rounded-4">
                    <div class="card-body p-4">
                        <h2 class="mb-4 text-center">Create a New newsletter</h2>

                        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                            <div class="mb-3">
                                <label for="title" class="form-label">newsletter Title</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">newsletter Description</label>
                                <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="date" class="form-label">newsletter Date</label>
                                <input type="date" class="form-control" id="date" name="date" required>
                            </div>
                            <div class="mb-3 d-flex gap-2">
                                <button type="submit" class="btn btn-primary flex-grow-1">Draft Newsletter</button>
                                <button type="submit" class="btn btn-secondary flex-grow-1">Publish Newsletter</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>