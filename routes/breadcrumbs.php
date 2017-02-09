<?php

// Dashboard
Breadcrumbs::register('dashboard', function ($breadcrumbs) {
    $breadcrumbs->push('Dashboard', route('dashboard'));
});

// Dashboard > Profile
Breadcrumbs::register('profile', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('My Profile', route('profile'));
});

// Dashboard > Profile
Breadcrumbs::register('client.index', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Clients', route('client.index'));
});

// Dashboard > Templates
Breadcrumbs::register('template.index', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Templates', route('template.index'));
});

// Dashboard > Templates > Create
Breadcrumbs::register('template.create', function ($breadcrumbs) {
    $breadcrumbs->parent('template.create');
    $breadcrumbs->push('Create template', route('template.create'));
});

// Dashboard > Templates > Edit
Breadcrumbs::register('template.edit', function ($breadcrumbs) {
    $breadcrumbs->parent('template.edit');
    $breadcrumbs->push('Edit template', route('template.edit'));
});

// Dashboard > Google+
Breadcrumbs::register('google-plus', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Google Plus', route('google-plus'));
});

// Dashboard > Facebook
Breadcrumbs::register('facebook', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Facebook', route('facebook'));
});

// Dashboard > LinkedIn
Breadcrumbs::register('linkedin', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('LinkedIn', route('linkedin'));
});