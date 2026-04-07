<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

/**
 * PostPolicy - Authorization logic related to Post
 *
 * Laravel Policy is a clean way to organize authorization logic.
 * This Policy defines what actions a User can perform on a Post.
 *
 * Role-based permissions:
 * - Author: can create, view, and edit their own posts (only in draft/rejected status)
 * - Editor: can view all pending posts and approve/reject them
 * - Admin: can do everything, has all Editor permissions
 */
class PostPolicy
{
    /**
     * Determine whether all published posts can be viewed
     * Everyone including guests can view published posts
     */
    public function viewAny(?User $user): bool
    {
        return true; // everyone can view published posts
    }

    /**
     * Determine whether a specific post can be viewed
     *
     * - Published posts can be viewed by everyone
     * - Draft/Pending/Rejected posts can only be viewed by owner and editor
     */
    public function view(?User $user, Post $post): bool
    {
        // Published post can be viewed by anyone
        if ($post->isPublished()) {
            return true;
        }

        // If not logged in, cannot view unpublished posts
        if (!$user) {
            return false;
        }

        // Post owner can view
        if ($post->user_id === $user->id) {
            return true;
        }

        // Editor and Admin can view all posts
        return $user->canManagePosts();
    }

    /**
     * Determine whether a new post can be created
     * Only authenticated users (both Author and Editor) can create posts
     */
    public function create(User $user): bool
    {
        return true; // any logged-in user can create a post
    }

    /**
     * Determine whether a post can be edited
     *
     * Rules:
     * - Author can only edit their own post
     * - Author can only edit in draft or rejected status
     * - Editor cannot edit any post (only approve/reject)
     */
    public function update(User $user, Post $post): bool
    {
        // Only post owner can edit
        if ($post->user_id !== $user->id) {
            return false;
        }

        // Can only edit in draft or rejected status
        // Cannot edit once it is pending or published
        return $post->isDraft() || $post->isRejected();
    }

    /**
     * Determine whether a post can be deleted
     *
     * Rules:
     * - Author can only delete their own draft post
     * - Editor cannot delete any post
     */
    public function delete(User $user, Post $post): bool
    {
        // Only post owner can delete
        if ($post->user_id !== $user->id) {
            return false;
        }

        // Can only delete in draft status
        return $post->isDraft();
    }

    /**
     * Determine whether a post can be submitted (draft -> pending)
     *
     * When an Author submits a draft post, it becomes pending
     * and goes to the Editor's approval queue
     */
    public function submit(User $user, Post $post): bool
    {
        // Only post owner can submit
        if ($post->user_id !== $user->id) {
            return false;
        }

        // Can only submit draft or rejected posts
        return $post->isDraft() || $post->isRejected();
    }

    /**
     * Determine whether a post can be approved (pending -> published)
     * Editor and Admin can approve pending posts
     */
    public function approve(User $user, Post $post): bool
    {
        // Only Editor and Admin can approve
        if (!$user->canManagePosts()) {
            return false;
        }

        // Can only approve pending posts
        return $post->isPending();
    }

    /**
     * Determine whether a post can be rejected (pending -> rejected)
     * Editor and Admin can reject pending posts
     */
    public function reject(User $user, Post $post): bool
    {
        // Only Editor and Admin can reject
        if (!$user->canManagePosts()) {
            return false;
        }

        // Can only reject pending posts
        return $post->isPending();
    }

    /**
     * Determine whether pending posts list can be viewed
     * Editor and Admin can view the list of pending posts
     */
    public function viewPending(User $user): bool
    {
        return $user->canManagePosts();
    }
}
