<?php
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

interface WebhookHandlerInterface
{
    public function handle(Request $request): Response;
}
