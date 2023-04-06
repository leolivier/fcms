<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TreeFamily;
use App\Models\TreeIndividual;
use App\Models\TreeRelationship;
use App\Models\User;
use App\FamilyTree;
use Carbon\Carbon;

class FamilyTreeController extends Controller
{
    /**
     * Show the family tree main page
     *
     * @return Illuminate\View\View
     */
    public function index()
    {
        return $this->showTree(0);
    }

    /**
     * showTree 
     * 
     * Show the family tree main page for a specific user.
     * 
     * @param int $individualId 
     * @return Illuminate\View\View
     */
    public function showTree (int $individualId)
    {
        $familyTree = new FamilyTree($individualId);

        if ($individualId == 0)
        {
            $familyTree->setUserId(Auth()->user()->id);

            if (!$familyTree->doesCurrentUserHaveFamilyTree())
            {
                return $familyTree->getEmptyTree();
            }
        }

        $tree = $familyTree->getFamilyTree();

        $allUsers = User::where('id', '>', 1)
            ->where('id', '!=', Auth()->user()->id)
            ->orderBy('name', 'desc')
            ->get()
            ->pluck('name', 'id');

        return view('tree.index', [
            'users' => $allUsers,
            'tree'  => $tree,
        ]);
    }

    /**
     * Save the relationship to the db
     *
     * @return Illuminate\View\View
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'       => ['sometimes', 'nullable', 'integer'],
            'family_id'     => ['sometimes', 'nullable', 'integer'],
            'individual_id' => ['sometimes', 'nullable', 'integer'],
            'given_name'    => ['sometimes', 'nullable', 'max:255'],
            'surname'       => ['sometimes', 'nullable', 'max:255'],
            'maiden'        => ['sometimes', 'nullable', 'max:255'],
            'alias'         => ['sometimes', 'nullable', 'max:255'],
            'nickname'      => ['sometimes', 'nullable', 'max:255'],
            'name_prefix'   => ['sometimes', 'nullable', 'max:255'],
            'name_suffix'   => ['sometimes', 'nullable', 'max:255'],
            'status'        => ['sometimes', 'in:living,deceased'],
            'dob'           => ['sometimes', 'nullable', 'before_or_equal:today'],
            'dod'           => ['sometimes', 'nullable', 'before_or_equal:today'],
            'sex'           => ['sometimes', 'in:U,O,M,F'],
        ]);

        $familyId = 0;

        //
        // Create the family record
        //
        if (!$request->has('family_id'))
        {
            $family = new TreeFamily();

            if ($request->has('description'))
            {
                $family->description = $request->description;
            }

            $family->created_user_id = Auth()->user()->id;
            $family->updated_user_id = Auth()->user()->id;

            $family->save();

            $familyId = $family->id;
        }
        else
        {
            $familyId = $request->family_id;
        }

        //
        // Create the individual record
        //
        $individual = new TreeIndividual();

        $individual->family_id       = $familyId;
        $individual->created_user_id = Auth()->user()->id;
        $individual->updated_user_id = Auth()->user()->id;
        $individual->given_name      = $request->has('given_name') ? $request->given_name : _gettext('Unknown');

        if ($request->has('user_id'))
        {
            $individual->user_id = $request->user_id;
        }
        if ($request->has('surname'))
        {
            $individual->surname = $request->surname;
        }
        if ($request->has('maiden'))
        {
            $individual->maiden = $request->maiden;
        }
        if ($request->has('alias'))
        {
            $individual->alias = $request->alias;
        }
        if ($request->has('nickname'))
        {
            $individual->nickname = $request->nickname;
        }
        if ($request->has('name_prefix'))
        {
            $individual->name_prefix = $request->name_prefix;
        }
        if ($request->has('name_suffix'))
        {
            $individual->name_suffix = $request->name_suffix;
        }
        if ($request->has('sex'))
        {
            $individual->sex = $request->sex;
        }
        if ($request->has('status'))
        {
            $individual->living = $request->status == 'living' ? true : false;
        }
        if ($request->filled('dob'))
        {
            $individual->dob_year  = substr($request->dob, 0, 4);
            $individual->dob_month = substr($request->dob, 5, 2);
            $individual->dob_day   = substr($request->dob, 8, 2);
        }
        if ($request->filled('dod'))
        {
            $individual->dod_year  = substr($request->dod, 0, 4);
            $individual->dod_month = substr($request->dod, 5, 2);
            $individual->dod_day   = substr($request->dod, 8, 2);
        }

        $individual->save();

        //
        // Create the relationship record
        //
        if ($request->has('relationship'))
        {
            $familyTree = new FamilyTree();

            // parent
            if ($request->input('relationship') == 'parent')
            {
                $familyTree->addNewParent($individual, $request);
            }
            // spouse
            if ($request->input('relationship') == 'spouse')
            {
                $relationship = new TreeRelationship;

                $relationship->individual_id   = $individual->id;
                $relationship->family_id       = $familyId;
                $relationship->relationship    = $individual->sex == 'F' ? 'WIFE' : 'HUSB';
                $relationship->created_user_id = Auth()->user()->id;
                $relationship->updated_user_id = Auth()->user()->id;

                $relationship->save();
            }
            // sibling
            if ($request->input('relationship') == 'sibling')
            {
                $familyTree->addNewSibling($individual, $request);
            }
            // child
            if ($request->input('relationship') == 'child')
            {
                $relationship = new TreeRelationship;

                $relationship->individual_id   = $individual->id;
                $relationship->family_id       = $familyId;
                $relationship->relationship    = 'CHIL';
                $relationship->created_user_id = Auth()->user()->id;
                $relationship->updated_user_id = Auth()->user()->id;

                $relationship->save();
            }
        }
        // For new individual records, we still make a default HUSB/WIFE relationship for the user
        // as kind of a head of household record
        else
        {
            $relationship = new TreeRelationship;

            $relationship->created_user_id = Auth()->user()->id;
            $relationship->updated_user_id = Auth()->user()->id;

            $relationship->individual_id = $individual->id;
            $relationship->family_id     = $familyId;
            $relationship->relationship  = $individual->sex == 'F' ? 'WIFE' : 'HUSB';

            $relationship->save();
        }

        return redirect()->route('familytree');
    }

    public function show(int $id)
    {
        $individual = TreeRelationship::from('tree_relationships as r')
            ->select('i.*', 'avatar', 'r.family_id', 'r.relationship')
            ->join('tree_individuals as i', 'r.individual_id', '=', 'i.id')
            ->leftJoin('users as u', 'i.user_id', '=', 'u.id')
            ->where('i.id', $id)
            ->first()
            ->toArray();

        if (!empty($individual['dob_year']) && !empty($individual['dob_month']) && !empty($individual['dob_day']))
        {
            $dob = Carbon::CreateFromDate($individual['dob_year'], $individual['dob_month'], $individual['dob_day'], Auth()->user()->timezone);

            $individual['dob'] = $dob->isoFormat('LL');
        }
        if (!empty($individual['dod_year']) && !empty($individual['dod_month']) && !empty($individual['dod_day']))
        {
            $dod = Carbon::CreateFromDate($individual['dod_year'], $individual['dod_month'], $individual['dod_day'], Auth()->user()->timezone);

            $individual['dod'] = $dod->isoFormat('LL');
        }

        return view('tree.individual', [
            'individual' => $individual,
        ]);
    }

    public function edit(int $id)
    {
        $individual = TreeIndividual::findOrFail($id);
        dd($individual->toArray());
    }

    public function update(int $id)
    {
        $individual = TreeIndividual::findOrFail($id);
        dd($individual->toArray());
    }
}
