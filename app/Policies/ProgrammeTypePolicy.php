<?php

namespace App\Policies;

use App\Models\ProgrammeType;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProgrammeTypePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $this->checkAbilityByAction($user, 'afficher type de programme');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProgrammeType  $programmeType
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, ProgrammeType $programmeType)
    {
        $this->viewAny($user);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $this->checkAbilityByAction($user, 'ajouter type de programme');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProgrammeType  $programmeType
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, ProgrammeType $programmeType)
    {
        return $this->checkAbilityByAction($user, 'modifier type de programme');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProgrammeType  $programmeType
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, ProgrammeType $programmeType)
    {
        return $this->checkAbilityByAction($user, 'supprimer type de programme');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProgrammeType  $programmeType
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, ProgrammeType $programmeType)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProgrammeType  $programmeType
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, ProgrammeType $programmeType)
    {
        //
    }

    /**
     * 
     * @param App\Models\User $user the current user
     * @param string $action the name of the action to check
     * @return bool whether if the current user can do the specified action
     */
    function checkAbilityByAction(User $user, $action)
    {
        if ($user->admin) {
            $role = 'admin';
        } elseif ($user->medical_assistant) {
            $role = 'medical assistant';
        } elseif ($user->juridique_assistant) {
            $role = 'juridique assistant';
        } elseif ($user->social_assistant) {
            $role = 'social assistant';
        } elseif ($user->intervenant) {
            $role = 'intervenant';
        }
        $his_permissions = Role::where('role_nom', $role)
                            ->first()
                            ->permissions
                            ->map(function ($action, $key) {
                                return $action['action_nom'];
                            });
        $he_can = $his_permissions->contains($action);
        return $he_can;
    }
}
