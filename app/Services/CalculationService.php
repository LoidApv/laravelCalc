<?php

namespace App\Services;

use App\Models\Calculation;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Validator;

class CalculationService
{
    public function setLeft(?float $left, Session $session): Calculation
    {
        $calc = $this->getCalcFromSession($session);
        $left ??= $calc->getAttribute('result');

        Validator::make(['leftValue' => $left], [
            'leftValue' => ['required', 'numeric'],
        ])->validate();

        $calc = new Calculation();
        $calc->setAttribute('leftValue', $left);
        $calc->save();
        $session->put('leftValue', $left);

        return $this->getCalcFromSession($session);
    }

    public function setRight(float $right, Session $session): Calculation
    {
        $calc = new Calculation();
        $calc->setAttribute('rightValue', $right);
        $calc->save();
        $session->put('rightValue', $right);

        return $this->getCalcFromSession($session);
    }

    public function setOperation(string $operation, Session $session): Calculation
    {
        $calc = new Calculation();
        $calc->setAttribute('operation', $operation);
        $calc->save();
        $session->put('operation', $operation);

        return $this->countResult($session);
    }

    public function countResult(Session $session): Calculation
    {

        $calc = $this->getCalcFromSession($session);
        if (!$calc->getAttribute('leftValue') && $calc->getAttribute('result')) {
            $calc->setAttribute('leftValue', $calc->getAttribute('result'));
        }

        $validator = Validator::make($calc->toArray(), [
            'leftValue' => ['required'],
            'rightValue' => ['required'],
            'operation' => ['required', "zeroDiv:{$calc->getAttribute('rightValue')}"],
        ], [
            'operation.zero_div' => "Zero division",
        ]);
        $validator->addExtension('zeroDiv', function ($attribute, $value, $parameters, $validator) {
            $isZeroDiv = $value === '/' && $parameters[0] === 0;
            return !$isZeroDiv;
        });
        $validator->validate();

        $left = $calc->getAttribute('leftValue');
        $right = $calc->getAttribute('rightValue');
        $op = $calc->getAttribute('operation');
        $result = match ($op){
            '+' => $left + $right,
            '-' => $left - $right,
            '*' => $left * $right,
            '/' => $left / $right,
        };

        $calc->setAttribute('result', $result);
        $calc->save();
        $session->put('result', $result);

        return $calc;
    }

    public function getCalcFromSession(Session $session): Calculation
    {
        $calc = new Calculation();
        !$session->get('leftValue') ?: $calc->setAttribute('leftValue', $session->get('leftValue'));
        !$session->get('rightValue') ?: $calc->setAttribute('rightValue', $session->get('rightValue'));
        !$session->get('operation') ?: $calc->setAttribute('operation', $session->get('operation'));
        !$session->get('result') ?: $calc->setAttribute('result', $session->get('result'));

        return $calc;
    }

    public function clearSession(Session $session): void
    {
        $session->forget(['leftValue', 'rightValue', 'operation', 'result']);
    }

    public function deleteHistory(): void
    {
        Calculation::query()->delete();
    }
}
