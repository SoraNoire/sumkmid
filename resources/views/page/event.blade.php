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
			<div class="infinite-scroll">
			@foreach ($var['events'] as $event)
			<?php  $meta = PostHelper::get_post_meta($event->id); ?>
			<div class="post event the-row {{ $meta['open_at'] > Carbon::now() ? 'active' : '' }}" id="event-{{ $event->id }}">
				<div class="event-datetime col-3">
					<span>{{ date('d M Y', strtotime($meta['open_at'])) }}</span>
					<span>{{ date('H:i', strtotime($meta['open_at'])) }} WIB - {{ $meta['closed_at'] != '' ? date('H:i', strtotime($meta['closed_at'])).' WIB' : 'till drop' }} </span>
				</div>
				<div class="event-timeline">
					<div class="event-indicator"></div>
				</div>
				<div class="event-content col-9">
					<div class="event-title">{{ $event->title }}</div>
					<div class="event-datetime-mobile">
							<span>{{ date('d M Y', strtotime($meta['open_at'])) }} |</span>
							<span>{{ date('H:i', strtotime($meta['open_at'])) }} WIB - {{ $meta['closed_at'] != '' ? date('H:i', strtotime($meta['closed_at'])).' WIB' : 'till drop' }} </span>
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
										<p>- {{ $mentor }}</p>
									@endforeach
								</td>
							</tr>
							@endif

							@if ($meta['event_type'] == 'offline')
							@if ($meta['htm'] != '')
							<tr class="htm">
								<td>HTM :</td>
								<td>Rp {{ number_format($meta['htm']) }}</td>
							</tr>
							@endif

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
						</table>
					</div>
					<div class="lihat-detail button orange-shadow" onclick="show_event_detail('event-{{ $event->id }}')">Lihat Detail</div>
					<div class="lihat-sedikit button orange-shadow" onclick="show_less_event_detail('event-{{ $event->id }}')">Sembunyikan Detail</div>
					@if ($meta['event_type'] == 'online')
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
			{{ $var['events']->links() }}
			</div>
		</div>

		<!-- status elements -->
		<div class="scroller-status ">
		  	<div class="event the-row">
				<div class="col-3"></div>
				<div class="event-timeline">
					<div class="event-indicator"></div>
				</div>
				<div class="col-9 infinity-scroll-message">
					<div class="loadingItems">
						<span class="infinite-scroll-last">Tidak Ada Event Lagi</span>
					</div>
				</div>
		  	</div>
		</div>
	</div>
</div>
@endsection