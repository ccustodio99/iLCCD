<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\JobOrder;
use App\Models\Requisition;
use App\Models\Ticket;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('query');
        $results = [];

        if ($query) {
            $like = "%{$query}%";

            $results['tickets'] = Ticket::query()
                ->where('subject', 'like', $like)
                ->orWhere('description', 'like', $like)
                ->get();

            $results['jobOrders'] = JobOrder::query()
                ->where('description', 'like', $like)
                ->orWhere('job_type', 'like', $like)
                ->get();

            $results['requisitions'] = Requisition::query()
                ->where('purpose', 'like', $like)
                ->orWhere('remarks', 'like', $like)
                ->get();

            $results['documents'] = Document::query()
                ->where('title', 'like', $like)
                ->orWhere('description', 'like', $like)
                ->get();
        }

        return view('search.index', [
            'query' => $query,
            'results' => $results,
        ]);
    }
}
