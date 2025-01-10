<!-- Create Modal -->
<div class="modal fade" id="addGiftModal" tabindex="-1" aria-labelledby="addGiftModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addGiftModalLabel">Add New Gift</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addGiftForm" method="POST" action="/create/gift" enctype="multipart/form-data">
                    <div class="row g-3">
                        <!-- Columns -->
                        <div class="col-md-6 col-12">
                            <div class="mb-3">
                                <label for="modal-gift_id" class="form-label">Gift_id</label>
                                <input type="text" class="form-control" name="gift_id" disabled>
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="mb-3">
                                <label for="modal-gift_name" class="form-label">Gift name</label>
                                <input type="text" class="form-control" name="gift_name" value="New gift">
                            </div>
                        </div>
                        <div class="col-md-12 col-12">
                            <div class="mb-3">
                                <label for="modal-category_id" class="form-label">Category</label>
                                <select class="form-select" name="category_id" id="modal-category_id" required>
                                    <option value="1" selected>Girl</option>
                                    <option value="2">Boy</option>
                                    <option value="3">Neutral</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="mb-3">
                                <label for="modal-price" class="form-label">Price</label>
                                <input type="number" class="form-control" name="price" value="100">
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="mb-3">
                                <label for="modal-description" class="form-label">Description</label>
                                <textarea type="text" class="form-control" name="description"
                                    rows="1">Brand new gift</textarea>
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="mb-3">
                                <label for="modal-stock_quantity" class="form-label">Stock quantity</label>
                                <input type="number" class="form-control" name="stock_quantity" value="1">
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="mb-3">
                                <label for="modal-pic" class="form-label">Pic</label>
                                <input type="hidden" name="MAX_FILE_SIZE" value="5000000">
                                <input type="file" class="form-control" name="pic">
                            </div>
                        </div>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- List of the gifts -->
<table class="table table-bordered caption-top" id="modify-table">
    <caption class="">Modify gifts</caption>
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Category</th>
            <th>Price</th>
            <th>Description</th>
            <th>Stock</th>
            <th>Picture</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($gifts as $gift) { ?>
            <tr>
                <td><?= $gift['gift_id'] ?></td>
                <td><?= $gift['gift_name'] ?></td>
                <td><?= $gift['category_name'] ?></td>
                <td><?= $gift['price'] ?></td>
                <td><?= $gift['description'] ?></td>
                <td><?= $gift['stock_quantity'] ?></td>
                <td><img src="/assets/img/gifts/<?= $gift['pic'] ?>" alt="Gift" width="50"></td>
                <td>
                    <a class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modifyModal"
                        data-gift_id="<?= $gift['gift_id'] ?>" data-gift_name="<?= $gift['gift_name'] ?>"
                        data-category_id="<?= $gift['category_id'] ?>" data-price="<?= $gift['price'] ?>"
                        data-description="<?= $gift['description'] ?>" data-stock_quantity="<?= $gift['stock_quantity'] ?>"
                        data-pic="<?= $gift['pic'] ?>">Edit</a>

                    <a href="/delete/gift/?gift_id=<?= $gift['gift_id'] ?>" class="btn btn-danger delete-btn">Delete</a>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<!-- MODIFY/UPDATE Modal -->
<div class="modal fade" id="modifyModal" tabindex="-1" aria-labelledby="modifyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modifyModalLabel">Modify Gift</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="modifyForm" method="POST" action="/update/gift" enctype="multipart/form-data">
                    <div class="row g-3">
                        <!-- Columns -->
                        <div class="col-md-6 col-12">
                            <div class="mb-3">
                                <label for="modal-gift_id" class="form-label">Gift ID</label>
                                <input type="text" class="form-control" name="gift_id" readonly>
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="mb-3">
                                <label for="modal-gift_name" class="form-label">Gift Name</label>
                                <input type="text" class="form-control" name="gift_name">
                            </div>
                        </div>
                        <div class="col-md-12 col-12">
                            <div class="mb-3">
                                <label for="modal-category_id" class="form-label">Category</label>
                                <select class="form-select" name="category_id" id="modal-category_id" required>
                                    <option value="1">Girl</option>
                                    <option value="2">Boy</option>
                                    <option value="3">Neutral</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="mb-3">
                                <label for="modal-price" class="form-label">Price</label>
                                <input type="number" class="form-control" name="price" step="0.01">
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="mb-3">
                                <label for="modal-description" class="form-label">Description</label>
                                <textarea type="text" class="form-control" name="description" rows="1"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="mb-3">
                                <label for="modal-stock_quantity" class="form-label">Stock Quantity</label>
                                <input type="number" class="form-control" name="stock_quantity">
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="mb-3">
                                <label for="modal-pic" class="form-label">Pic</label>
                                <input type="hidden" name="MAX_FILE_SIZE" value="5000000">
                                <!-- Previous -->
                                <input type="hidden" name="previous_pic">
                                <!-- New -->
                                <input type="file" class="form-control" name="pic">
                            </div>
                        </div>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">Modify</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>