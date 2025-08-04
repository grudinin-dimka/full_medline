<template>
	<General />

	<VueWikiTitle>
		<VueIcon name="add" width="14" height="14" fill="var(--primary-color)" />
		Добавление цен
	</VueWikiTitle>
	<VueWikiParagraph>
		1. Для того, чтобы добавить цены с клиниками, вам необходимо нажать но кнопку «Добавить»,
		которая находится внизу блока «Список цен».
	</VueWikiParagraph>
	<VueWikiParagraph>
		2. После нажатия на кнопку «Добавить», откроется модальное окно, в котором вы сможете добавить
		новый файл с ценами. В качестве файлов можно использовать файлы форматов
		<span :style="{ color: 'var(--primary-color)' }">.ods</span>,
		<span :style="{ color: 'var(--primary-color)' }">.xls</span>,
		<span :style="{ color: 'var(--primary-color)' }">.xlsx</span>. Правила оформления файлов цен описаны в разделе «Шаблон» в блоке «Цены».
	</VueWikiParagraph>
	<VueWikiParagraph>
		3. В случае, когда при нажатии на кнопку «Добавить» внизу модального окна появляется сообщение
		об ошибке, это означает, что вы не заполнили обязательные поля, которые помечаются красным
		цветом.
	</VueWikiParagraph>
	<VueWikiParagraph>
		4. После соблюдения предыдущих пунктов, вы сможете добавить новую клинику, нажав на кнопку
		«Добавить» внизу модального окна.
	</VueWikiParagraph>

	<VueWikiTitle>
		<VueIcon name="remove" width="16" height="16" fill="var(--primary-color)" />
		Удаление цен
	</VueWikiTitle>
	<VueWikiParagraph>
		1. Для того, чтобы удалить цены из файлов, вам необходимо нажать на кнопку удалить в строке с
		выбранным файлом цен, тем самым пометить на удаление.
	</VueWikiParagraph>
	<VueWikiParagraph>
		2. Для полного удаления клиник, которые помечены на удаление, нажмите на кнопку «Сохранить».
	</VueWikiParagraph>

	<VueWikiTitle>
		<VueIcon name="download" width="14" height="16" fill="var(--primary-color)" />
		Выгрузка цен
	</VueWikiTitle>
	<VueWikiParagraph>
		1. При нажатии на эту кнопку, начнётся генерация файлов списков цен в формате для загрузки на
		сайт ProDoctorov в виде архива.
	</VueWikiParagraph>
	<VueWikiParagraph>
		2. После генарции начнётся загрузка архива с файлами цен, после чего вам необходимо будет
		распоковать его и загрузить на сайт ProDoctorov соответствующий клинике по названию файл.
	</VueWikiParagraph>
</template>

<script>
import VueWikiTitle from "../../VueWikiTitle.vue";
import VueWikiParagraph from "../../VueWikiParagraph.vue";

import General from "../General.vue";

import VueIcon from "../../../icon/VueIcon.vue";

export default {
	components: { VueWikiTitle, VueWikiParagraph, General, VueIcon },
};
</script>

<style scoped></style>
