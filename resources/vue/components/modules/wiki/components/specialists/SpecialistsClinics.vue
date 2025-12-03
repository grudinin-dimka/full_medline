<template>
	<General />

	<VueWikiTitle>
		<VueIcon name="Add" width="24" height="24" fill="var(--primary-color)" />
		Добавление клиники
	</VueWikiTitle>
	<VueWikiParagraph>
		1. Для того, чтобы добавить клинику, вам необходимо нажать но кнопку «Добавить», которая
		находится внизу блока «Клиники».
	</VueWikiParagraph>
	<VueWikiParagraph>
		2. После нажатия на кнопку «Добавить», откроется модальное окно, в котором вы сможете добавить
		новую клинику.
	</VueWikiParagraph>
	<VueWikiParagraph>
		3. В случае, когда при нажатии на кнопку «Добавить» внизу модального окна появляется сообщение
		об ошибке, это означает, что вы не заполнили обязательные поля, которые помечаются красным
		цветом.
	</VueWikiParagraph>
	<VueWikiParagraph>
		4. После соблюдения предыдущих пунктов, вы сможете добавить новую клинику, нажав на
		кнопку «Добавить» внизу модального окна.
	</VueWikiParagraph>

	<VueWikiTitle>
		<VueIcon name="Delete" width="24" height="24" fill="var(--primary-color)" />
		Удаление клиники
	</VueWikiTitle>
	<VueWikiParagraph>
		1. Для того, чтобы удалить клинику, вам необходимо нажать на кнопку удалить в строке с
		выбранной клиникой, тем самым пометить на удаление.
	</VueWikiParagraph>
	<VueWikiParagraph>
		2. Для полного удаления клиник, которые помечены на удаление, нажмите на кнопку
		«Сохранить».
	</VueWikiParagraph>

	<VueWikiTitle>
		<VueIcon name="Edit" width="24" height="24" fill="var(--primary-color)" />
		Изменение клиники
	</VueWikiTitle>
	<VueWikiParagraph>
		1. Для того, чтобы изменить клинику, вам необходимо нажать на кнопку «Изменить» левой
		кнопкой мышки. После этого, вы можете редактировать её содержимое.
	</VueWikiParagraph>
	<VueWikiParagraph>
		2. Для обновления значений клиники нажмите на кнопку «Обновить».
	</VueWikiParagraph>
	<VueWikiParagraph> 
		3. Для сохранения изменений нажмите на кнопку «Сохранить». 
		</VueWikiParagraph>
</template>

<script>
import VueWikiTitle from "../../VueWikiTitle.vue";
import VueWikiParagraph from "../../VueWikiParagraph.vue";

import General from "../General.vue";

export default {
	components: { VueWikiTitle, VueWikiParagraph, General },
};
</script>

<style scoped></style>
