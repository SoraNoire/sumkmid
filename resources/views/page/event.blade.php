@extends('layouts.publicbase')

@section('content')
<div class="breadcrumb">
	<div class="container">
		<h2>Event</h2>
	</div>
</div>

<div id="event-archive">
	<div class="container">
		<div class="archive-list">
			@foreach ($events as $event)
			<div class="post event the-row {{ $event->open_at > Carbon::now() ? 'active' : '' }}" id="event-{{ $event->id }}">
				<div class="event-datetime col-3">
					<span>{{ date('d F Y', strtotime($event->open_at)) }}</span>
					<span>{{ date('H:i', strtotime($event->open_at)) }} WIB - {{ $event->closed_at != '' ? date('H:i', strtotime($event->closed_at)).' WIB' : 'till drop' }} </span>
				</div>
				<div class="event-timeline">
					<div class="event-indicator"></div>
				</div>
				<div class="event-content col-9">
					<div class="event-title">{{ $event->title }}</div>
					<div class="event-desc">
						{!! $event->content !!}
					</div>
					<div class="event-meta">
						<table>
							<tr class="mentor">
								<td>Speaker :</td>
								<td>
									@if (count($event->mentors) > 0)
										@foreach ($event->mentors as $mentor)
											<p>- {{ $mentor }}</p>
										@endforeach
									@else 
										<p>-</p>
									@endif
								</td>
							</tr>

							@if ($event->event_type == 'offline')
							@if ($event->htm != '')
							<tr class="htm">
								<td>HTM :</td>
								<td>Rp {{ number_format($event->htm) }}</td>
							</tr>
							@endif

							<tr class="tempat">
								<td>Tempat :</td>
								<td>
									<p>{{ $event->location }}</p>
									@if ($event->gmaps_url != '')
									<i class="fa fa-map-marker" aria-hidden="true">&nbsp;&nbsp;</i><a href="{{ $event->gmaps_url }}">view on google maps</a>
									@endif
								</td>
							</tr>
							@endif
						</table>
					</div>
					<div class="lihat-detail button orange-shadow" onclick="show_event_detail('event-{{ $event->id }}')">Lihat Detail</div>
					<div class="lihat-sedikit button orange-shadow" onclick="show_less_event_detail('event-{{ $event->id }}')">Sembunyikan Detail</div>
					@if ($event->event_type == 'online')
					<div class="event-buttons">
						<a href="#" class="join-event button orange-shadow">Join</a>
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
					</div>
					@endif
				</div>
			</div>
			@endforeach
		</div>

		<!-- status elements -->
		<div class="scroller-status">
		  	<div class="event the-row">
				<div class="col-3"></div>
				<div class="event-timeline">
					<div class="event-indicator"></div>
				</div>
				<div class="col-9 infinity-scroll-message">
					<span class="infinite-scroll-request"><img src="/img/infinity-load.svg"> Memuat Event</span>
					<span class="infinite-scroll-last">Tidak Ada Event Lagi</span>
					<span class="infinite-scroll-error">Tidak Ada Event Lagi</span>
				</div>
		  	</div>
		</div>

		<!-- pagination has path -->
		<p class="pagination">
		  	<a class="pagination__next" href="{{ url('/event/page/'.$next) }}">Next page</a>
		</p>
	</div>
</div>


@endsection