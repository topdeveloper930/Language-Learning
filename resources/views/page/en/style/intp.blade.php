@php
	$tplConfig->page_meta_description = 'In school, work, and other public scenarios such as language learning groups, the INTP is the structured one of the bunch. They want to understand how things are put together, how they work, and how that structure is helping the process. They thrive...';
@endphp
<section class="mb-xl">
	<div class="container">
		<header class="page-header position-centered">
			<h1 class="page-title">You are a <span class="color-primary">{{ $shareContent }}</span> learner</h1>
			<p class="page-subtitle"><b class="color-primary">I</b>ntroverted I<b class="color-primary">N</b>tuitive <b class="color-primary">T</b>hinking <b class="color-primary">P</b>erceiving</p>
			<!--<p class="page-subtitle"><b>I</b>NTROVERTED I<b>N</b>TUITIVE <b>T</b>HINKING <b>J</b>UDGING</p>-->
		</header>
	</div>
</section>
<section class="mb-xxl">
	<div class="container md">
        <h2>How {{ strtoupper($result -> style) }}'s acquire, memorize and recollect information:</h2>
        <p>
            In school, work, and other public scenarios such as language learning groups, the INTP is the structured one of the bunch. They want to understand how things are put together, how they work, and how that structure is helping the process. They thrive on in-depth scenarios and big-picture topics, always wanting to grasp why and how everything works. The INTP learner desires to search for, study, and understand the building blocks of an issue. They like to take things apart and put them back together, if only to know exactly why they were set up that way in the first place.
          </p>
          <p>
            When it comes to theoretical situations, the INTP learner grasps material well and can accurately describe how it connects to the problem at hand. They are able to thrive on verbal and written communication, and on watching how things are done. In fact, the INTP learner often actively despises group learning activities and games. They aren’t always the fastest to connect socially with others in group environments, instead wanting to process information on their own in order to build a basic understanding.
          </p>
          <p>
            Once the INTP grasps material, they can quickly and accurately apply it to a real-life situation. They excel in using theoretical knowledge to solve real-world problems – even enjoying the process along the way. Because of this, complicated but rewarding projects such as learning a new language often appeal to them. They also handle stress well. The INTP is not known to balance work equally from day to day – they often prefer intensive cramming sessions followed by a period of relaxation.
          </p>
    </div>
</section>
<section class="mb-xxl">
	<div class="container lg">
		<div class="columns align-stretch">
			<div class="column col-6 col-12-md">
				<div class="card">
                    <h3>Optimal learning siutations:</h3>
                    <ul>
                      <li>Intensive, fast-paced environments.</li>
                      <li>Solo learning and study time is allowed and encouraged.</li>
                      <li>Information is presented from multiple vantage points and opinions.</li>
                      <li>They can connect the dots between how something works and why it works that way.</li>
                      <li>Nuanced material and methods of presentation.</li>
                    </ul>
                </div>
            </div>
            <div class="column col-6 col-12-md">
				<div class="card">
                    <h3>Where the {{ strtoupper($result -> style) }} struggles</h3>
                    <ul>
                        <li>Material is full of unimportant details.</li>
                        <li>Material does not allow further understanding of a situation or solution.</li>
                        <li>Group learning environments.</li>
                        <li>Material is presented quickly and without structured order.</li>
                    </ul>
                </div>
            </div>
        </div>
	</div>
</section>