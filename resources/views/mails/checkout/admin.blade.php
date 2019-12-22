<?php
/** @var \App\Services\Orders\CheckoutResult $checkout */
?>
@extends('mails.layout')

@section('body')
    <p>New order requires processing.</p>
    <p><a href="{{ $checkout->order->getAdminLink() }}">Link to admin order page</a></p>
    <p>Total price: {{ $checkout->order->total_price }} {{ $checkout->order->currency }}</p>
@endsection
