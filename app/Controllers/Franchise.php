<?php

namespace App\Controllers;

use App\Models\FranchiseApplicationModel;
use App\Models\FranchiseSupplyModel;
use App\Models\FranchiseModel;

class Franchise extends BaseController
{
    public function dashboard()
    {
        return view('franchise/dashboard');
    }

    public function franchises()
    {
        $model = new FranchiseModel();
        $franchises = $model->orderBy('franchise_id', 'DESC')->findAll();

        return view('franchise/franchises', ['franchises' => $franchises]);
    }

    public function applications()
    {
        $model = new FranchiseApplicationModel();
        $applications = $model->orderBy('application_id', 'DESC')->findAll();

        return view('franchise/applications', [
            'applications' => $applications,
        ]);
    }

    public function approve($id)
    {
        $model = new FranchiseApplicationModel();
        $model->update($id, ['status' => 'Approved']);

        return redirect()->back();
    }

    public function deny($id)
    {
        $model = new FranchiseApplicationModel();
        $model->update($id, ['status' => 'Denied']);

        return redirect()->back();
    }

    public function supplies($applicationId)
    {
        $appModel = new FranchiseApplicationModel();
        $supplyModel = new FranchiseSupplyModel();

        $application = $appModel->find($applicationId);
        if (!$application) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Application not found');
        }

        $supplies = $supplyModel->getByApplication($applicationId);

        return view('franchise/supplies', [
            'application' => $application,
            'supplies' => $supplies,
        ]);
    }

    public function addSupply($applicationId)
    {
        $request = service('request');

        $itemName = trim((string) $request->getPost('item_name'));
        $quantity = (int) $request->getPost('quantity');
        $supplyDate = $request->getPost('supply_date') ?: date('Y-m-d');

        if ($itemName === '' || $quantity <= 0) {
            return redirect()->back();
        }

        $supplyModel = new FranchiseSupplyModel();
        $supplyModel->insert([
            'application_id' => $applicationId,
            'item_name' => $itemName,
            'quantity' => $quantity,
            'supply_date' => $supplyDate,
        ]);

        return redirect()->back();
    }

    public function allocation()
    {
        return view('franchise/allocation');
    }

    public function inventory()
    {
        return view('franchise/inventory');
    }

    public function orders()
    {
        return view('franchise/orders');
    }

    public function reports()
    {
        return view('franchise/reports');
    }

    public function settings()
    {
        return view('franchise/settings');
    }
}
