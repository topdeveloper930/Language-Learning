@php
	$tplConfig->page_meta_title = $header = ('teacher' == $userType) ? 'Teaching style quiz' : 'Learning style quiz';
@endphp

<script>
	var quizLanguage = '{{ $quizLanguage }}';
</script>
<main id="root" v-cloak>
	<section class="mb-lg">
		<div class="container">
			<header class="page-header position-centered">
				<h1 class="page-title">{{ $header }}</h1>
				<p class="page-subtitle">Find out your {{ 'teacher' == $userType ? 'teaching' : 'learning' }} style in just 5 minutes</p>
			</header>
		</div>
	</section>

	<section>
		<div class="container md text-align-center">
			@if('teacher' == $userType)
				<p>This quiz calculates your teaching style by using the personality archetypes created by the famous psychologist Carl Jung.</p>
			@else
				<p>This quiz is a guide to help you understand how you learn{{ $quizLanguage ? ' ' . $quizLanguage : '' }} best - whether it's through visual representation, audio descriptions, active engagement or by any other means suited to your learning style and personality.</p>
			@endif

			<hr>

			<p class="text-align-center mb-lg"><strong>Select the first answer that comes to mind, based on how closely you relate to each of the following scenarios:</strong></p>

			<form  method="post" class="quiz-form">

				<range-item v-for="question in currentPageQuestions"
							:label="formatQuestion(question.base, question.id)"
							:key="question.id"
							:data-key="question.id"
							:min-label="question.leftmost"
							:max-label="question.rightmost"
							:value="question.answer"
				>
				</range-item>

				<div class="next-prev-buttons form-group text-align-center mt-lg">
					<button class="button" :class="{'secondary':page == 1, 'primary':page>1}"
											@click.prevent="prevQuestions"
											:disabled="page == 1" >
							<i class="far fa-angle-left"></i> Previous
					</button>
					<button class="button primary" @click.prevent="nextQuestions"
												   v-if="page != Math.ceil(questions.length / limit)">Next
												   <i class="far fa-angle-right"></i>
					</button>
					<button class="button primary" @click.prevent="viewQuizResult"
													v-if="page == Math.ceil(questions.length / limit)">View Your Result
													<i class="far fa-angle-right"></i>
					</button>
				</div>
			</form>

		</div>
	</section>
</main>