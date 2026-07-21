@extends('layouts.app')

@section('content')
<x-hero />
<x-hero-stats-counter />
<x-about-new />




<x-modules :courses="$courses" />
<x-college-tie-up />

<x-how-work />



<x-dashboard-preview />
<x-certificate-showcase />
<x-partnership-colleges />
<x-our-verticals />
<x-stat />

<x-master-internship />

<!-- <x-services /> -->
<x-why-choose-us />
<x-choose />
<x-faq />

@endsection
