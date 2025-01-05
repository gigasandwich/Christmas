<!-- Create Modal -->
<div class="modal fade" id="addGiftModal" tabindex="-1" aria-labelledby="addGiftModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addGiftModalLabel">Add New Gift</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addGiftForm" method="post" action="/create/gift">
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
                                <div class="card d-flex">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="category_id" value="1"
                                            id="category-1" checked>
                                        <label class="form-check-label" for="category-1">
                                            Girl
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="category_id" value="2"
                                            id="category-2" checked>
                                        <label class="form-check-label" for="category-2">
                                            Boy
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="category_id" value="3"
                                            id="category-3" checked>
                                        <label class="form-check-label" for="category-3">
                                            Neutral
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="mb-3">
                                <label for="modal-price" class="form-label">Price</label>
                                <input type="number" class="form-control" name="price" value="0">
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
                        data-category_name="<?= $gift['category_name'] ?>" data-price="<?= $gift['price'] ?>"
                        data-description="<?= $gift['description'] ?>"
                        data-stock_quantity="<?= $gift['stock_quantity'] ?>">Edit</a>
                    <a href="/delete/gift/<?= $gift['gift_id'] ?>" class="btn btn-danger delete-btn">Delete</a>
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
                <h5 class="modal-title" id="modifyModalLabel">Modify gift</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="" id="modifyForm">
                    <div class="row g-3">
                        <!-- Columns -->
                        <div class="col-md-6 col-12">
                            <div class="mb-3">
                                <label for="modal-gift_id" class="form-label">Gift_id</label>
                                <input type="text" class="form-control" name="gift_id" disabled value="<?= $gift['gift_id'] ?>">
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="mb-3">
                                <label for="modal-gift_name" class="form-label">Gift name</label>
                                <input type="text" class="form-control" name="gift_name" value="New gift" value="<?= $gift['gift_name'] ?>">
                            </div>
                        </div>
                        <div class="col-md-12 col-12">
                            <div class="mb-3">
                                <label for="modal-category_id" class="form-label">Category</label>
                                <div class="card d-flex">
                                    <?= $gift['category_id'] ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="category_id" value="1" 
                                            id="category-1" checked>
                                        <label class="form-check-label" for="category-1">
                                            Girl
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="category_id" value="2"
                                            id="category-2" checked>
                                        <label class="form-check-label" for="category-2">
                                            Boy
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="category_id" value="3"
                                            id="category-3" checked>
                                        <label class="form-check-label" for="category-3">
                                            Neutral
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="mb-3">
                                <label for="modal-price" class="form-label">Price</label>
                                <input type="number" class="form-control" name="price" value="<?= $gift['price'] ?>">
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="mb-3">
                                <label for="modal-description" class="form-label">Description</label>
                                <textarea type="text" class="form-control" name="description"
                                    rows="1"><?= $gift['description'] ?></textarea>
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="mb-3">
                                <label for="modal-stock_quantity" class="form-label">Stock quantity</label>
                                <input type="number" class="form-control" name="stock_quantity" value="<?= $gift['stock_quantity'] ?>">
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="mb-3">
                                <label for="modal-pic" class="form-label">Pic</label>
                                <input type="hidden" name="MAX_FILE_SIZE" value="5000000">
                                <input type="file" class="form-control" name="pic" value="<?= $gift['pic'] ?>">
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
</div>