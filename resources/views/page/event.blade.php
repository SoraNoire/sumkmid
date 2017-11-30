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
			<!-- <div class="post event the-row active" id="event-12">
				<div class="event-datetime col-3">
					<span>13 Desember 2017</span>
					<span>08:00 WIB - till drop</span>
				</div>
				<div class="event-timeline">
					<div class="event-indicator"></div>
				</div>
				<div class="event-content col-9">
					<div class="event-title">Ngobrol Bareng Saudagar Muda</div>
					<div class="event-desc">
						<p>Monotonectally communicate plug-and-play imperatives before wireless collaboration and idea-sharing. Rapidiously facilitate dynamic synergy and flexible channels. Efficiently enhance effective opportunities without market-driven infrastructures. Interactively reinvent bleeding-edge bandwidth and frictionless niches. Intrinsicly communicate resource maximizing products after leveraged leadership.</p>
					</div>
					<div class="event-meta">
						<div class="mentor the-row">
							<div class="col-1">Speaker:</div>
							<div class="col-4">
								<p>-Indra Kusumawati</p>
								<p>-Badri Suprangat</p>
							</div>
						</div>
							
						<div class="tempat the-row">
							<div class="col-1">Tempat:</div>
							<div class="col-4">
								<p>Rumah Makan Padang Pariangan Jl. Kusuma Bangsa 31, Kab. Bogor</p>
								<i class="fa fa-map-marker" aria-hidden="true">&nbsp;&nbsp;</i><a href="#">view on google maps</a>
							</div>
						</div>
					</div>
					<div class="lihat-detail button orange-shadow" onclick="show_event_detail('event-12')">Lihat Detail</div>
				</div>
			</div>

			<div class="post event the-row" id="event-13">
				<div class="event-datetime col-3">
					<span>13 Desember 2017</span>
					<span>08:00 WIB - till drop</span>
				</div>
				<div class="event-timeline">
					<div class="event-indicator"></div>
				</div>
				<div class="event-content col-9">
					<div class="event-title"><img src="/img/event-icon.svg"> Ngobrol Bareng Saudagar Muda</div>
					<div class="event-desc">
						<p>Monotonectally communicate plug-and-play imperatives before wireless collaboration and idea-sharing. Rapidiously facilitate dynamic synergy and flexible channels. Efficiently enhance effective opportunities without market-driven infrastructures. Interactively reinvent bleeding-edge bandwidth and frictionless niches. Intrinsicly communicate resource maximizing products after leveraged leadership.</p>
					</div>
					<div class="event-meta">
						<div class="mentor the-row">
							<div class="col-1">Speaker:</div>
							<div class="col-4">
								<p>-Indra Kusumawati</p>
								<p>-Badri Suprangat</p>
							</div>
						</div>						
					</div>
					<a href="#" class="join-event button orange-shadow">Join</a>
					<div class="share-event button blue blue-shadow" onclick="show_event_sharer('event-13')">Bagikan ke Teman</div>
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
					</div>
				</div>
			</div> -->
			@foreach ($events as $event)
			<div class="post event the-row" id="event-{{$event->id}}">
				<div class="event-datetime col-3">
					<span>13 Desember 2017</span>
					<span>08:00 WIB - till drop</span>
				</div>
				<div class="event-timeline">
					<div class="event-indicator"></div>
				</div>
				<div class="event-content col-9">
					<div class="event-title"><img src="/img/event-icon.svg"> Ngobrol Bareng Saudagar Muda</div>
					<div class="event-desc">
						<p>Monotonectally communicate plug-and-play imperatives before wireless collaboration and idea-sharing. Rapidiously facilitate dynamic synergy and flexible channels. Efficiently enhance effective opportunities without market-driven infrastructures. Interactively reinvent bleeding-edge bandwidth and frictionless niches. Intrinsicly communicate resource maximizing products after leveraged leadership.</p>
					</div>
					<div class="event-meta">
						<div class="mentor the-row">
							<div class="col-1">Speaker:</div>
							<div class="col-4">
								<p>-Indra Kusumawati</p>
								<p>-Badri Suprangat</p>
							</div>
						</div>						
					</div>
					<a href="#" class="join-event button orange-shadow">Join</a>
					<div class="share-event button blue blue-shadow" onclick="show_event_sharer('event-{{$event->id}}')">Bagikan ke Teman</div>
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
					</div>
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