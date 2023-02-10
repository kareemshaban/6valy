<div class="modal fade" id="tabPaymentModal" tabindex="-1" role="dialog" aria-labelledby="smallModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document" style="min-width: 1000px">
        <div class="modal-content">
            <div class="modal-header">
                <label>Card Information</label>
                <button type="button" class="close not-print" data-bs-dismiss="modal" aria-label="Close"
                        style="color: red; font-size: 20px; font-weight: bold;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="smallBody">
                <div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <strong>Credit Card</strong>
                                    <small>enter your card details</small>
                                </div>
                                <form action="{{route('card_token')}}" method="post" class="needs-validation">
                                    @csrf
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">


                                                <label for="name">Name</label>
                                                <input class="form-control" id="name" name="name" type="text"
                                                       placeholder="Enter your name" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="ccnumber">Credit Card Number</label>


                                                <div class="input-group">
                                                    <input class="form-control" type="text"
                                                           placeholder="0000 0000 0000 0000" autocomplete="email"
                                                           id="number" name="number" required>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">
                                                            <i class="mdi mdi-credit-card"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-sm-4">
                                            <label for="ccmonth">Month</label>
                                            <select class="form-control" id="exp_month" name="exp_month" required>
                                                <option value="" selected>select Month</option>
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                                <option value="6">6</option>
                                                <option value="7">7</option>
                                                <option value="8">8</option>
                                                <option value="9">9</option>
                                                <option value="10">10</option>
                                                <option value="11">11</option>
                                                <option value="12">12</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label for="ccyear">Year</label>
                                            <select class="form-control" id="exp_year" name="exp_year" required>
                                                <option value="" selected>select Year</option>
                                                <option value="2023">2023</option>
                                                <option value="2024">2024</option>
                                                <option value="2025">2025</option>
                                                <option value="2026">2026</option>
                                                <option value="2027">2027</option>
                                                <option value="2028">2028</option>
                                                <option value="2029">2029</option>
                                                <option value="2030">2030</option>
                                                <option value="2031">2031</option>
                                                <option value="2032">2032</option>
                                                <option value="2033">2033</option>
                                                <option value="2034">2034</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="cvv">CVV/CVC</label>
                                                <input class="form-control" id="cvc" name="cvc" type="text" required
                                                       placeholder="123">
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="card-footer">
                                    <button class="btn btn-sm btn-success float-right" type="submit">
                                        <i class="mdi mdi-gamepad-circle"></i> Continue
                                    </button>
                                    <button class="btn btn-sm btn-danger" type="button" id="reset">
                                        <i class="mdi mdi-lock-reset"></i> Reset
                                    </button>
                                </div>
                                </form>
                            </div>

                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>
