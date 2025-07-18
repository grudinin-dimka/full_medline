<!DOCTYPE html>
<html>

<head>
	<!-- пропишем заголовок окна -->
	<title>Технические работы</title>

	<!-- не забываем о кодировке -->
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

	<!-- подключаем стили -->
	<style>
		body {
			font-family: Verdana, Arial, sans-serif;
			color: #000;
			height: 100dvh;
			margin: 0px;
		}

		.block {
			display: flex;
			flex-direction: column;
			align-items: center;
			justify-content: center;
			gap: 20px;

			height: 100%;
			width: 100%;
		}

		.template {
			display: flex;
			flex-direction: column;
			gap: 10px;
			text-align: center;

			padding: 20px;
		}

		h1 {
			font-size: 1.5rem;
		}

		p {
			font-size: 1rem;
			margin: 0;
		}

		@media screen and (max-width: 500px) {
			html {
				font-size: 20px;
			}
		}
	</style>
</head>

<body>
	<div class="block">
		<section class="template">
			<h1>На сайте ведутся технические работы</h1>
			<p>
				На текущий момент наш интернет-ресурс недоступен. Матрица не сломалась. Мы просто
				решили сделать ее немного лучше.
			</p>
			<p>Обновите страницу через пару минут. Все будет работать.</p>
		</section>
	</div>
</body>

</html>