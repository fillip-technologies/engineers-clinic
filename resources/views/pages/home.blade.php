@extends('layouts.app')

@section('content')
<x-hero />

<x-stat />

<x-modules :courses="$courses" />
<x-how-work />
<x-hero-2 />
<x-dashboard-preview />
<x-college-tie-up />
<x-partners />
<!-- <x-services /> -->
<x-why-choose-us />
<x-choose />
<x-faq />

@endsection
