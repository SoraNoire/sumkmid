@extends('layouts.publicbase')

@section('content')

<div class="breadcrumb">
	<div class="container">
		<h2>Video</h2>
	</div>
</div>

<section class="singleVideo">
	<div class="videoHeading">
		<div class="container">
			<h3>{{$var['video']->title}}</h3>
			<div class="postMeta">
				<div class="postCategory">
					<span class="icon i-paper"></span>
					<span>ide, usaha, branding</span>
				</div>
				<div class="postTag">
					<span class="icon i-tag"></span>
					<span>kripik, packaging</span>
				</div>
			</div>
		</div>
	</div>
	<div class="videoContainer">
		<div class="container">
			<iframe src="https://www.youtube.com/embed/oT7cJU1I0iQ" frameborder="0" gesture="media" allow="encrypted-media" allowfullscreen></iframe>
		</div>
	</div>
	<div class="videoDescription">
		<div class="container">
			<div class="row">
				<div class="col-9">
					<div class="wrapDescription styledText">
						<p>Completely incubate focused human capital through equity invested intellectual capital. Enthusiastically whiteboard resource maximizing total linkage via enabled systems. Professionally scale interactive initiatives with competitive e-tailers. Efficiently productivate worldwide testing procedures whereas seamless technology. Phosfluorescently plagiarize economically sound potentialities whereas client-based value.</p>
						<p>Compellingly <u>deliver wireless</u> <b>leadership skills</b> and client-based communities. Efficiently fashion cross-media markets vis-a-vis covalent bandwidth. Uniquely unleash go forward systems without cutting-edge e-services. Objectively reinvent cutting-edge core competencies <i>before economically</i> sound intellectual capital. Continually envisioneer 24/365 materials before market positioning content.</p>
						<p>Dynamically re-engineer plug-and-play intellectual capital whereas quality partnerships. Energistically impact bleeding-edge vortals for revolutionary web-readiness. Efficiently utilize resource-leveling "outside the box" thinking after visionary networks. Rapidiously enhance high-quality niche markets after high-payoff human capital. Synergistically e-enable enterprise-wide web-readiness after intermandated niches.</p>
						<p>Authoritatively coordinate process-centric human <a href="#">capital</a> through emerging benefits. Rapidiously leverage other's interactive niche markets through state of the art processes. Dramatically aggregate interdependent functionalities and functional data. Synergistically morph ubiquitous portals for future-proof "outside the box" thinking. Continually unleash maintainable value rather than reliable users.</p>
						<ol>
							<li>
								<h5>Monotonectally integrate cross-unit action items rather than promote market-driven bandwidth. </h5>
								<p>Rapidiously initiate front-end materials before 24/365 catalysts for change. Competently deliver extensible deliverables after tactical sources. Monotonectally maintain out-of-the-box bandwidth through tactical e-commerce. Conveniently re-engineer accurate testing procedures whereas cross-unit ROI. Compellingly supply premier leadership for enterprise process improvements.</p>
							</li>
							<li>
								<h5>Proactively network one-to-one technologies and impactful installed architectures. </h5>
								<p>Continually unleash low-risk high-yield expertise via top-line benefits. Seamlessly brand enabled internal or "organic" sources whereas emerging vortals. Collaboratively orchestrate adaptive technologies without effective quality vectors. Continually reconceptualize performance based manufactured products for integrated expertise. Uniquely fashion real-time markets with premier processes.</p>
							</li>
							<li>
								<h5>Proactively network one-to-one technologies and impactful installed architectures.</h5>
								<p>Completely scale high-quality materials through robust communities. Authoritatively evisculate unique interfaces before cooperative deliverables. Dramatically plagiarize transparent e-business for e-business total linkage. Completely myocardinate bricks-and-clicks innovation before frictionless interfaces. Energistically evisculate multifunctional niche markets without global applications.</p>
								<blockquote>
										Competently architect an expanded array of architectures after synergistic customer service. Competently synthesize client-centric testing procedures with backend methodologies. Globally scale proactive leadership without high-quality total linkage. Efficiently productize long-term high-impact opportunities without client-centered catalysts for change. Seamlessly optimize sustainable applications through dynamic opportunities.
								</blockquote>
							</li>
						</ol>
						<ul>
							<li>
								<h5>Monotonectally integrate cross-unit action items rather than promote market-driven bandwidth. </h5>
								<p>Rapidiously initiate front-end materials before 24/365 catalysts for change. Competently deliver extensible deliverables after tactical sources. Monotonectally maintain out-of-the-box bandwidth through tactical e-commerce. Conveniently re-engineer accurate testing procedures whereas cross-unit ROI. Compellingly supply premier leadership for enterprise process improvements.</p>
							</li>
							<li>
								<h5>Proactively network one-to-one technologies and impactful installed architectures.</h5>
								<p>Continually unleash low-risk high-yield expertise via top-line benefits. Seamlessly brand enabled internal or "organic" sources whereas emerging vortals. Collaboratively orchestrate adaptive technologies without effective quality vectors. Continually reconceptualize performance based manufactured products for integrated expertise. Uniquely fashion real-time markets with premier processes.</p>
							</li>
							<li>
								<h5>Proactively network one-to-one technologies and impactful installed architectures.</h5>
								<p>Continually unleash low-risk high-yield expertise via top-line benefits. Seamlessly brand enabled internal or "organic" sources whereas emerging vortals. Collaboratively orchestrate adaptive technologies without effective quality vectors. Continually reconceptualize performance based manufactured products for integrated expertise. Uniquely fashion real-time markets with premier processes.</p>
							</li>
						</ul>
					</div>
					<div class="attachmentVideo">
						<h4>Unduh Dokumen Pendukung :</h4>
						<ul>
							<li>
								<a href="#">
									<span class="icon i-download"></span>
									<span>Action sheet 1</span>
								</a>
							</li>
							<li>
								<a href="#">
									<span class="icon i-download"></span>
									<span>Action sheet 1</span>
								</a>
							</li>
						</ul>
					</div>
					<div class="videoNavigation">
						<div class="prevVideo">
							<a href="#">
								<span class="icon i-prev"></span>
								<span>video sebelumnya</span>
							</a>
						</div>
						<div class="nextVideo">
							<a href="#">
								<span>video selanjutnya</span>
								<span class="icon i-next"></span>
							</a>
						</div>
					</div>
				</div>
				<div class="col-3">
					<div class="sidebarVideo">
						<div class="sidebar kategoriWidget">
							<h4 class="sidebarDefaultHeading">Kategori</h4>
							<ul>
								<li><a href="#">Branding</a></li>
								<li><a href="#">Marketing</a></li>
								<li><a href="#">Promosi</a></li>
								<li><a href="#">Ide Usaha</a></li>
							</ul>
						</div>
						<div class="sidebar searchVideo">
							<h4 class="sidebarDefaultHeading">Kategori</h4>
							<div class="searchBox">
								<div class="inputWrap">
									<form action="">
										<input placeholder="Kata Kunci" type="text" name="searchVideo" id="">
										<button><span class="icon i-search"></span></button>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

@endsection