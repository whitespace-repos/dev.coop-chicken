<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">

<div class="container" style="margin-top:40px">
    <div class="row">
        <div class="well col-xs-10 col-sm-10 col-md-6 col-xs-offset-1 col-sm-offset-1 col-md-offset-3">
            <div class="row">
                <div class="col-xs-6 col-sm-6 col-md-6">
                    <address>
                        <strong>{{ $carts->first()->attributes->customer->name }}</strong>
                        <br>
                        <kbd title="Phone">Mobile:</kbd> {{ $carts->first()->attributes->customer->phone }}
                    </address>
                </div>
                <div class="col-xs-6 col-sm-6 col-md-6 text-right">
                    <p>
                        <em>COOP-{{ auth()->user()->shop->shop_name .' / '. auth()->user()->shop->shop_id }}</em>
                    </p>
                    <p>
                        <em>Date: {{ \Carbon\Carbon::now() }}</em>
                    </p>
                    <p>
                        <em>Receipt #: {{ \Carbon\Carbon::now()->format('ymdHis') }}</em>
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="text-center">
                    <img src="/assets/img/logo.png" alt="logo">
                </div>
                </span>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>#</th>
                            <th class="text-center">Price</th>
                            <th class="text-center">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($carts as $cart)
                            <tr>
                                <td class="col-md-9"><em>{{ $cart->attributes->product->product_name }}</em></h4></td>
                                <td class="col-md-1"> {{ $cart->attributes->weight }} </td>
                                <td class="col-md-1 text-center">₹{{ $cart->attributes->rate }}</td>
                                <td class="col-md-1 text-center">₹{{ $cart->price }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td></td>
                            <td></td>
                            <td class="text-right">
                            <p>
                                <strong>Subtotal:</strong>
                            </p>
                            <p>
                                <strong>Tax:</strong>
                            </p></td>
                            <td class="text-center">
                            <p>
                                <strong>₹{{ $carts->sum('price') }}</strong>
                            </p>
                            <p>
                                <strong>₹{{ 0.00 }}</strong>
                            </p></td>
                        </tr>
                        @if($request->receiveAmount)
                            <tr style="text-decoration: line-through">
                                <td></td>
                                <td></td>
                                <td class="text-right"><h4><strong>Total: </strong></h4></td>
                                <td class="text-center text-danger"><h4><strong>₹{{ $carts->sum('price') + 0 }}</strong></h4></td>
                            </tr>

                            <tr>
                                <td></td>
                                <td></td>
                                <td class="text-right"><h4><strong>Received: </strong></h4></td>
                                <td class="text-center text-danger"><h4><strong>₹{{ $request->receiveAmount }}</strong></h4></td>
                            </tr>
                        @else
                            <tr>
                                <td></td>
                                <td></td>
                                <td class="text-right"><h4><strong>Total: </strong></h4></td>
                                <td class="text-center text-danger"><h4><strong>₹{{ $carts->sum('price') + 0 }}</strong></h4></td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>