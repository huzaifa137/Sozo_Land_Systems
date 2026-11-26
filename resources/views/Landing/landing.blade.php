@php
    $szV = @filemtime(public_path('assets/sozo/landing.css')) ?: 1;

    /*
     * Contact details shown in the "Talk to us" section.
     * Fill in what you want to publish. Anything left empty is simply not shown.
     */
    $szContact = [
        'phone' => '',      // e.g. '+256 700 000 000'
        'whatsapp' => '',   // digits only, e.g. '256700000000'
        'email' => '',      // e.g. 'info@sozoproperties.com'
        'office' => '',     // e.g. 'Plot 12, Kampala Road, Kampala'
    ];

    // Live listings from the system. If the database is unreachable the page still renders.
    try {
        $szEstates = \App\Models\Estate::orderByDesc('id')->limit(6)->get();
    } catch (\Throwable $e) {
        $szEstates = collect();
    }
    try {
        $szHouses = \Illuminate\Support\Facades\DB::table('houses')->where('status', '0')->orderByDesc('id')->limit(6)->get()
            ->filter(function ($h) {
                $imgs = json_decode($h->house_images, true);
                return !empty($imgs[0]);
            })->values();
    } catch (\Throwable $e) {
        $szHouses = collect();
    }

    $szPhotos = [
        'photo-1500382017468-9049fed747ef', 'photo-1472214103451-9374bd1c798e', 'photo-1441974231531-c6227db76b6e',
        'photo-1500530855697-b586d89ba3ee', 'photo-1501785888041-af3ef285b470', 'photo-1506905925346-21bda4d32df4',
    ];
    $szMoney = function ($v) {
        $d = preg_replace('/[^\d.]/', '', (string) $v);
        return $d !== '' ? number_format((float) $d) : $v;
    };
    $szHasContact = collect($szContact)->filter()->isNotEmpty();
@endphp
<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#0d1a16">
    <title>Sozo Properties | Land and homes in Uganda</title>
    <meta name="description" content="Browse Sozo Properties estates, plots and houses. Every payment is recorded, with receipts and agreements issued.">
    <link rel="icon" type="image/png" href="/assets/sozo/favicon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,500;0,9..144,600;0,9..144,700;1,9..144,500&family=Instrument+Sans:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="/assets/sozo/landing.css?v={{ $szV }}">
    <script src="/assets/sozo/sozo.js?v={{ $szV }}" defer></script>
    <script src="/assets/sozo/landing.js?v={{ $szV }}" defer></script>
    <script src="/assets/sozo/docthumb.js?v={{ $szV }}" defer></script>
</head>
<body>

<header class="nav">
    <div class="wrap">
        <a class="brand" href="{{ url('/') }}" aria-label="Sozo Property Consultants home">
            <img src="/assets/sozo/logo-light.png?v={{ $szV }}" alt="Sozo Property Consultants" width="403" height="379">
        </a>
        <nav class="links" id="menu" aria-label="Main">
            <a href="#offer">What we offer</a>
            @if ($szEstates->count())<a href="#estates">Estates</a>@endif
            @if ($szHouses->count())<a href="#houses">Houses</a>@endif
            <a href="#how">How it works</a>
            @if ($szHasContact)<a href="#contact">Contact</a>@endif
            <a class="m-only" href="{{ route('login') }}">Staff sign in</a>
        </nav>
        <a class="btn btn-brass" href="{{ route('login') }}">Staff sign in</a>
        <button class="burger" type="button" aria-label="Menu" aria-expanded="false" aria-controls="menu"><span></span></button>
    </div>
</header>

<main>
    <section class="hero" style="padding:0">
        <img class="hero-img" src="https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=2000&q=70" alt="" data-sz-fallback data-seed="3" fetchpriority="high">
        <div class="wrap">
            <div>
                <p class="eyebrow" style="color:var(--brass)">Sozo Properties, Uganda</p>
                <h1>Land and homes, with <em>every payment</em> on record.</h1>
                <p class="lead">Choose a plot in one of our estates or find a house that suits you. We issue a receipt for every payment and an agreement when you buy.</p>
                <div class="hero-cta">
                    <a class="btn btn-brass" href="#{{ $szEstates->count() ? 'estates' : 'offer' }}">{{ $szEstates->count() ? 'Browse estates' : 'See what we offer' }}</a>
                    <a class="btn btn-ghost" href="#how">How buying works</a>
                </div>
            </div>
            <aside class="hero-card" aria-label="Estate layout example">
                <div class="plan" data-sz-hero-plan></div>
                <ul>
                    <li>Plots laid out and numbered</li>
                    <li>Pay in full or by instalments</li>
                    <li>Receipts and agreements issued</li>
                </ul>
            </aside>
        </div>
        <span class="credit">Photo from Unsplash</span>
    </section>

    <section id="offer">
        <div class="wrap">
            <div class="head rv">
                <p class="eyebrow">What we offer</p>
                <h2>Everything to buy, pay for and own property.</h2>
                <p>From the first plot visit to the signed agreement, each step is handled and recorded by the Sozo team.</p>
            </div>
            <div class="offer">
                <article class="rv">
                    <div class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 20l5-9 4 6 3-4 6 7z"/><circle cx="17" cy="6" r="2"/></svg></div>
                    <h3>Plots in planned estates</h3>
                    <p>Surveyed estates with numbered plots, so you know exactly which piece of land is yours.</p>
                </article>
                <article class="rv">
                    <div class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 11l9-7 9 7"/><path d="M5 10v10h14V10"/><path d="M10 20v-6h4v6"/></svg></div>
                    <h3>Houses for sale</h3>
                    <p>Homes listed with their location, price, bedrooms and land tenure, ready to view.</p>
                </article>
                <article class="rv">
                    <div class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="3" width="16" height="18" rx="2"/><path d="M8 8h8M8 12h8M8 16h5"/></svg></div>
                    <h3>Receipts and agreements</h3>
                    <p>A receipt for each payment and a written agreement, so your purchase is documented from day one.</p>
                </article>
            </div>
        </div>
    </section>

    @if ($szEstates->count())
    <section id="estates" class="dark">
        <div class="wrap">
            <div class="head rv">
                <p class="eyebrow">Our estates</p>
                <h2>Find land in the estate that fits you.</h2>
                <p>Open an estate's plan to see how its plots are laid out, then contact the team to book a visit and choose yours.</p>
            </div>
            <div class="estates">
                @foreach ($szEstates as $e)
                    @php
                        $szKind = $e->documentKind();
                        $szUrl = $e->documentUrl();
                        $szPhoto = 'https://images.unsplash.com/' . $szPhotos[$e->id % count($szPhotos)] . '?auto=format&fit=crop&w=900&q=70';
                    @endphp
                    <article class="estate rv">
                        @if ($szKind)
                            <a class="cover sz-doc" href="{{ $szUrl }}" target="_blank" rel="noopener" aria-label="Open the {{ $e->estate_name }} {{ $szKind === 'pdf' ? 'PDF' : 'plan' }}">
                                @if ($szKind === 'pdf')
                                    <canvas data-sz-pdf="{{ $szUrl }}" aria-hidden="true"></canvas>
                                    <img class="sz-doc-alt" data-sz-alt="{{ $szPhoto }}" alt="" data-sz-fallback data-seed="{{ $e->id }}">
                                @else
                                    <img class="doc-img" src="{{ $szUrl }}" alt="Plan of {{ $e->estate_name }}" loading="lazy" data-sz-fallback data-seed="{{ $e->id }}">
                                @endif
                                <span class="doc-tag">View {{ $szKind === 'pdf' ? 'PDF' : 'plan' }}</span>
                                <span class="where"><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2a7 7 0 0 0-7 7c0 5 7 13 7 13s7-8 7-13a7 7 0 0 0-7-7zm0 9.5A2.5 2.5 0 1 1 12 6a2.5 2.5 0 0 1 0 5.5z"/></svg>{{ $e->location }}</span>
                            </a>
                        @else
                            <div class="cover">
                                <img src="{{ $szPhoto }}" alt="Land at {{ $e->estate_name }}" loading="lazy" data-sz-fallback data-seed="{{ $e->id }}">
                                <div class="sk" data-sz-sketch="{{ $e->id }}" aria-hidden="true"></div>
                                <span class="where"><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2a7 7 0 0 0-7 7c0 5 7 13 7 13s7-8 7-13a7 7 0 0 0-7-7zm0 9.5A2.5 2.5 0 1 1 12 6a2.5 2.5 0 0 1 0 5.5z"/></svg>{{ $e->location }}</span>
                            </div>
                        @endif
                        <div class="body">
                            <h3>{{ $e->estate_name }}</h3>
                            <div class="plots"><b>{{ $e->number_of_plots }}</b>plots</div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @if ($szHouses->count())
    <section id="houses">
        <div class="wrap">
            <div class="head rv">
                <p class="eyebrow">Houses for sale</p>
                <h2>Homes available now.</h2>
            </div>
            <div class="houses">
                @foreach ($szHouses as $h)
                    @php $imgs = json_decode($h->house_images, true); @endphp
                    <article class="house rv">
                        <div class="pic"><img src="{{ $imgs[0] }}" alt="House in {{ $h->location }}" loading="lazy" data-sz-fallback data-seed="{{ $h->id }}"></div>
                        <div class="info">
                            <div class="price"><small>UGX</small>{{ $szMoney($h->price) }}</div>
                            <p class="loc">{{ $h->location }}</p>
                            <div class="facts">
                                <span>{{ $h->bedroom }} {{ (int) $h->bedroom === 1 ? 'bedroom' : 'bedrooms' }}</span>
                                @if ($h->LandTenure)<span>{{ str_replace(' Land Tenure', '', $h->LandTenure) }}</span>@endif
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <section id="how" class="dark">
        <div class="wrap">
            <div class="head rv">
                <p class="eyebrow">How buying works</p>
                <h2>Four clear steps from visit to agreement.</h2>
            </div>
            <ol class="steps">
                <li class="rv"><h3>Choose your plot</h3><p>Pick an estate and a plot, or a house, with help from our team.</p></li>
                <li class="rv"><h3>Pay and get a receipt</h3><p>Make your first payment and receive a receipt for it straight away.</p></li>
                <li class="rv"><h3>Pay as agreed</h3><p>Settle in full, or pay in instalments. Each payment is recorded and receipted.</p></li>
                <li class="rv"><h3>Receive your agreement</h3><p>Your agreement is issued so the purchase is documented in writing.</p></li>
            </ol>
        </div>
    </section>

    <section>
        <div class="wrap trust">
            <div class="trust-img rv">
                <img src="https://images.unsplash.com/photo-1613490493576-7fde63acd811?auto=format&fit=crop&w=1100&q=70" alt="A modern house" loading="lazy" data-sz-fallback data-seed="8">
            </div>
            <div class="rv">
                <p class="eyebrow">Why Sozo</p>
                <h2 style="font-size:clamp(2rem,4vw,3.1rem)">A paper trail you can rely on.</h2>
                <div class="points">
                    <div><span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l8 3v6c0 5-3.5 8-8 9-4.5-1-8-4-8-9V6z"/><path d="M9 12l2 2 4-4"/></svg></span><span><b>Every payment recorded</b><p>Payments are logged against your plot, so your balance is always clear.</p></span></div>
                    <div><span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M6 3h9l4 4v14H6z"/><path d="M14 3v5h5M9 13h7M9 17h5"/></svg></span><span><b>Written agreements</b><p>An agreement is issued for your purchase, so the terms are in writing.</p></span></div>
                    <div><span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 1 1 12 0c0 6 2 7 2 7H4s2-1 2-7z"/><path d="M10 19a2 2 0 0 0 4 0"/></svg></span><span><b>Payment reminders</b><p>We follow up when an instalment falls due, so nothing slips by.</p></span></div>
                </div>
            </div>
        </div>
    </section>

    <section id="contact" style="padding-top:0">
        <div class="wrap">
            <div class="cta rv">
                <div>
                    <h2>Ready to see a plot or a house?</h2>
                    <p>Talk to the Sozo team to book a visit, ask about prices or start your purchase.</p>
                    <div class="contact-actions">
                        @if ($szContact['whatsapp'])<a class="btn btn-brass" href="https://wa.me/{{ $szContact['whatsapp'] }}">Chat on WhatsApp</a>@elseif ($szContact['phone'])<a class="btn btn-brass" href="tel:{{ preg_replace('/\s+/', '', $szContact['phone']) }}">Call us</a>@endif
                        <a class="btn btn-ghost" href="{{ route('login') }}">Staff sign in</a>
                    </div>
                </div>
                @if ($szHasContact)
                <div class="contact">
                    @if ($szContact['phone'])<a href="tel:{{ preg_replace('/\s+/', '', $szContact['phone']) }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2z"/></svg><span><small>Phone</small>{{ $szContact['phone'] }}</span></a>@endif
                    @if ($szContact['email'])<a href="mailto:{{ $szContact['email'] }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/></svg><span><small>Email</small>{{ $szContact['email'] }}</span></a>@endif
                    @if ($szContact['office'])<div><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s7-6 7-12a7 7 0 0 0-14 0c0 6 7 12 7 12z"/><circle cx="12" cy="9" r="2.5"/></svg><span><small>Office</small>{{ $szContact['office'] }}</span></div>@endif
                </div>
                @endif
            </div>
        </div>
    </section>
</main>

<footer>
    <div class="wrap">
        <span class="foot-brand"><img src="/assets/sozo/logo-light.png?v={{ $szV }}" alt="Sozo Property Consultants" width="403" height="379"><span>&copy; {{ date('Y') }} Sozo Property Consultants Limited</span></span>
        <nav aria-label="Footer">
            <a href="{{ route('privacy-policy') }}">Privacy policy</a>
            <a href="{{ route('login') }}">Staff sign in</a>
        </nav>
    </div>
</footer>

</body>
</html>
