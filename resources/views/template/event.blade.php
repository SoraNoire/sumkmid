@extends('layouts.publicbase')

@section('content')
<div class="breadcrumb">
	<div class="container">
		<ol itemscope itemtype="http://schema.org/BreadcrumbList">
            <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                <a itemprop="item" href="{{ route('public_home') }}"><span itemprop="name">Beranda</span></a> <i class="fa fa-angle-right" aria-hidden="true"></i>
                <meta itemprop="position" content="1" />
            </li>
            <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                <a itemprop="item" href="{{ url('/event') }}"><span itemprop="name">Event</span></a>
                <meta itemprop="position" content="2" />
            </li>
        </ol>
	</div>
</div>

<div id="event-archive">
	<div class="container">
		<div class="archive-list">
			<div class="infinite-scroll">
			@foreach ($var['events'] as $event)
			@php  $meta = PublicHelper::get_event_meta($event->id); @endphp
			<div class="post event the-row {{ $meta['open_at'] > Carbon::now() ? 'active' : '' }}" id="event-{{ $event->id }}">
				<div class="col-3">
					@if ( isset($event->featured_image) && $event->featured_image != '')
					<div class="eventPoster">
						<img src="{{ $event->featured_image }}">
					</div>
					@endif
				</div>
				<div class="event-timeline">
					<div class="event-indicator"></div>
				</div>
				<div class="event-content col-9">
					@if ( isset($event->featured_image) &&  $event->featured_image != '')
					<div class="eventPosterMobile">
						<img src="{{ $event->featured_image }}">
					</div>
					@endif
					<div class="eventTitleWrap">
						<div class="event-title">{{ $event->title }}</div>
						<div class="event-datetime">
								<span>{{ date('d M Y', strtotime($meta['open_at'])) }}</span>
								<span>{{ date('H:i', strtotime($meta['open_at'])) }} WIB - {{ date('d M Y', strtotime($meta['closed_at'])) > date('d M Y', strtotime($meta['open_at'])) ? date('d M Y H:i', strtotime($meta['closed_at'])).' WIB' : date('H:i', strtotime($meta['closed_at'])).' WIB' }} </span>
						</div>
					</div>
					<div class="event-desc hidden">
						{!! $event->content !!}
					</div>
					<div class="event-meta">
						<table>
							@if (count($meta['mentors']) > 0)
							<tr class="mentor">
								<td>Speaker :</td>
								<td>
									@foreach ($meta['mentors'] as $mentor)
										@if (is_array($mentor) && sizeof($mentor) > 0)
										<div class="mentorWrap">
											@if(isset($mentor->foto_profil))
											<div class="miniPhotoMentor" style="background-image: url('{{ $mentor->foto_profil }}');"></div>
											@else
											<div class="miniPhotoMentor" style="background-image: url('{{ asset('images/admin.png') }}');"></div>
											@endif
											@if (isset($mentor->username))
											<a href="{{ route('public_mentor_single',$mentor->username) }}">{{ $mentor->name ?? 'anonym' }}</a>
											@else
											{{ $mentor->name ?? 'anonym' }}
											@endif
										</div>
										@endif
									@endforeach
								</td>
							</tr>
							@endif

							@if ($meta['event_type'] == 'offline')
							@if ($meta['htm'] != '')
							<tr class="htm">
								<td>HTM :</td>
								@if ($meta['htm'] == 'free')
									<td>Free</td>
								@elseif ( is_array($meta['htm']) && count($meta['htm']) > 0 )
                                    <td>
                                    @foreach ($meta['htm'] as $htm)
                                    <p> Rp {{ number_format($htm->nominal) }} - {{ $htm->label }}</p>
                                    @endforeach
                                	</td>
                                @endif
							</tr>
							@endif

							@if ($meta['location'] != '' || $meta['gmaps_url'] != '')
							<tr class="tempat">
								<td>Tempat :</td>
								<td>
									<p>{{ $meta['location'] }}</p>
									@if ($meta['gmaps_url'] != '')
									<i class="fa fa-map-marker" aria-hidden="true">&nbsp;&nbsp;</i><a href="{{ $meta['gmaps_url'] }}">view on google maps</a>
									@endif
								</td>
							</tr>
							@endif
							@endif
						</table>
					</div>
					<div class="lihat-detail button orange-shadow" onclick="show_event_detail('event-{{ $event->id }}')">Lihat Detail</div>
					<div class="lihat-sedikit button orange-shadow" onclick="show_less_event_detail('event-{{ $event->id }}')">Sembunyikan Detail</div>
					<div class="event-buttons">
					@if ($meta['event_type'] == 'online')
						<a href="{{ $meta['event_url'] }}" class="join-event button orange-shadow">Join</a>
						<!-- <div class="share-event button blue blue-shadow" onclick="show_event_sharer('event-{{ $event->id }}')">Bagikan ke Teman</div>
						<div class="share-socmed">
							<ul>
								<li>
									<a href="#" class="button blue share-fb"></a>
								</li>
								<li>
									<a href="#" class="button blue share-tw"></a>
								</li>
								<li>
									<a href="#" class="button blue share-gplus"></a>
								</li>
							</ul>
						</div> -->
					@endif
					@if ( $meta['mentoring'] != '' )
					<a href="{{ url('/materi-mentoring/'.$meta['mentoring']) }}" class="button orange-shadow">Lihat Materi</a>
					@endif
					</div>
				</div>
			</div>
			@endforeach
			{{ $var['events']->links() }}
			</div>
		</div>
        <div class="scroller-status loading"> <div class="event the-row"> <div class="col-3"></div> <div class="event-timeline"> <div class="event-indicator"></div> </div> <div class="col-9 infinity-scroll-message"> <div class="loadingItems"> <span class="infinite-scroll-request"><img src="/img/infinity-load.svg"> Memuat Event</span> </div> </div> </div> </div>
</div>
@endsection
