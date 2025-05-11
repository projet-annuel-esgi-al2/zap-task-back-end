<?php
namespace app\Services\Contracts;


interface ThirsPartyTriggerService
{
    public function fetchTriggers(): array;
}