<?php

namespace App\Http\Controllers;

use App\Models\Calculation;
use App\Services\CalculationService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CalculationController extends Controller
{
    public function __construct(
        protected CalculationService $service
    )
    {
    }

    public function page(Request $request): View
    {
        $this->service->clearSession($request->session());
        return view('calculation', ['calc' => null]);
    }

    public function setLeftValue(Request $request): string
    {
        $data = $request->validate([
            'leftValue' => ['nullable', 'numeric'],
        ]);
        $calc = $this->service->setLeft($data['leftValue'], $request->session());
        return $calc->toJson();
    }

    public function setRightValue(Request $request): string
    {
        $data = $request->validate([
            'rightValue' => ['required', 'numeric'],
        ]);
        $calc = $this->service->setRight($data['rightValue'], $request->session());
        return $calc->toJson();
    }

    public function setOperation(Request $request): string
    {
        $data = $request->validate([
            'operation' => ['required', 'in:+,-,*,/'],
        ]);
        $calc = $this->service->setOperation($data['operation'], $request->session());
        return $calc->toJson();
    }

    public function getResult(Request $request): string
    {
        //$request->headers->add(["accept" => "application/json"]);
        $calc = $this->service->countResult($request->session());
        return $calc->toJson();
    }

    public function history(Request $request)
    {
        $page = $request->get('page', 1);
        return Calculation::all()->forPage($page, 15);
    }

    public function deleteHistory(Request $request): void
    {
        $this->service->deleteHistory();
    }
}
