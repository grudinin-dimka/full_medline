<template>
	<bubble-menu :editor="editor" :tippy-options="{ duration: 100 }" v-if="false">
		<div class="bubble__menu">
			<button
				@click="editor.chain().focus().toggleBold().run()"
				:class="{ 'is-active': editor.isActive('bold') }"
			>
				Bold
			</button>
			<button
				@click="editor.chain().focus().toggleItalic().run()"
				:class="{ 'is-active': editor.isActive('italic') }"
			>
				Italic
			</button>
			<button
				@click="editor.chain().focus().toggleStrike().run()"
				:class="{ 'is-active': editor.isActive('strike') }"
			>
				Strike
			</button>
		</div>
	</bubble-menu>

	<div class="tiptap__container">
		<div v-if="editor && editable" class="tiptap__buttons">
			<div class="tiptap__buttons-body">
				<!-- Заголовки -->
				<div class="tiptap__buttons-item">
					<button
						@click="editor.chain().focus().toggleHeading({ level: 1 }).run()"
						:class="{ 'is-active': editor.isActive('heading', { level: 1 }) }"
					>
						<svg
							width="18"
							height="13"
							viewBox="0 0 14 10"
							xmlns="http://www.w3.org/2000/svg"
						>
							<path d="M0 10V0H2V4H6V0H8V10H6V6H2V10H0ZM12 10V2H10V0H14V10H12Z" />
						</svg>
					</button>
					<button
						@click="editor.chain().focus().toggleHeading({ level: 2 }).run()"
						:class="{ 'is-active': editor.isActive('heading', { level: 2 }) }"
					>
						<svg
							width="23"
							height="15"
							viewBox="0 0 18 10"
							xmlns="http://www.w3.org/2000/svg"
						>
							<path
								d="M0 10V0H2V4H6V0H8V10H6V6H2V10H0ZM10 10V6C10 5.45 10.1958 4.97917 10.5875 4.5875C10.9792 4.19583 11.45 4 12 4H16V2H10V0H16C16.55 0 17.0208 0.195833 17.4125 0.5875C17.8042 0.979167 18 1.45 18 2V4C18 4.55 17.8042 5.02083 17.4125 5.4125C17.0208 5.80417 16.55 6 16 6H12V8H18V10H10Z"
							/>
						</svg>
					</button>
					<button
						@click="editor.chain().focus().toggleHeading({ level: 3 }).run()"
						:class="{ 'is-active': editor.isActive('heading', { level: 3 }) }"
					>
						<svg
							width="23"
							height="15"
							viewBox="0 0 18 10"
							xmlns="http://www.w3.org/2000/svg"
						>
							<path
								d="M0 10V0H2V4H6V0H8V10H6V6H2V10H0ZM10 10V8H16V6H12V4H16V2H10V0H16C16.55 0 17.0208 0.195833 17.4125 0.5875C17.8042 0.979167 18 1.45 18 2V8C18 8.55 17.8042 9.02083 17.4125 9.4125C17.0208 9.80417 16.55 10 16 10H10Z"
							/>
						</svg>
					</button>
				</div>

				<template v-if="options.includes('format')">
					<!-- Форматирование -->
					<div class="tiptap__buttons-item">
						<button
							@click="editor.chain().focus().toggleBold().run()"
							:class="{ 'is-active': editor.isActive('bold') }"
						>
							<svg
								width="11"
								height="14"
								viewBox="0 0 11 14"
								xmlns="http://www.w3.org/2000/svg"
							>
								<path
									d="M0 14V0H5.525C6.60833 0 7.60833 0.333333 8.525 1C9.44167 1.66667 9.9 2.59167 9.9 3.775C9.9 4.625 9.70833 5.27917 9.325 5.7375C8.94167 6.19583 8.58333 6.525 8.25 6.725C8.66667 6.90833 9.12917 7.25 9.6375 7.75C10.1458 8.25 10.4 9 10.4 10C10.4 11.4833 9.85833 12.5208 8.775 13.1125C7.69167 13.7042 6.675 14 5.725 14H0ZM3.025 11.2H5.625C6.425 11.2 6.9125 10.9958 7.0875 10.5875C7.2625 10.1792 7.35 9.88333 7.35 9.7C7.35 9.51667 7.2625 9.22083 7.0875 8.8125C6.9125 8.40417 6.4 8.2 5.55 8.2H3.025V11.2ZM3.025 5.5H5.35C5.9 5.5 6.3 5.35833 6.55 5.075C6.8 4.79167 6.925 4.475 6.925 4.125C6.925 3.725 6.78333 3.4 6.5 3.15C6.21667 2.9 5.85 2.775 5.4 2.775H3.025V5.5Z"
								/>
							</svg>
						</button>

						<button
							@click="editor.chain().focus().toggleItalic().run()"
							:class="{ 'is-active': editor.isActive('italic') }"
						>
							<svg
								width="13"
								height="14"
								viewBox="0 0 13 14"
								xmlns="http://www.w3.org/2000/svg"
							>
								<path d="M0 14V11.5H4L7 2.5H3V0H13V2.5H9.5L6.5 11.5H10V14H0Z" />
							</svg>
						</button>

						<button
							@click="editor.chain().focus().toggleUnderline().run()"
							:class="{ 'is-active': editor.isActive('underline') }"
						>
							<svg
								width="14"
								height="18"
								viewBox="0 0 14 18"
								xmlns="http://www.w3.org/2000/svg"
							>
								<path
									d="M0 18V16H14V18H0ZM7 14C5.31667 14 4.00833 13.475 3.075 12.425C2.14167 11.375 1.675 9.98333 1.675 8.25V0H4.25V8.4C4.25 9.33333 4.48333 10.0917 4.95 10.675C5.41667 11.2583 6.1 11.55 7 11.55C7.9 11.55 8.58333 11.2583 9.05 10.675C9.51667 10.0917 9.75 9.33333 9.75 8.4V0H12.325V8.25C12.325 9.98333 11.8583 11.375 10.925 12.425C9.99167 13.475 8.68333 14 7 14Z"
								/>
							</svg>
						</button>

						<button
							@click="editor.chain().focus().toggleSubscript().run()"
							:class="{ 'is-active': editor.isActive('subscript') }"
						>
							<svg
								width="18"
								height="16"
								viewBox="0 0 18 16"
								xmlns="http://www.w3.org/2000/svg"
							>
								<path
									d="M13.125 16V14C13.125 13.7167 13.2208 13.4792 13.4125 13.2875C13.6042 13.0958 13.8417 13 14.125 13H16.125V12H13.125V11H16.125C16.4083 11 16.6458 11.0958 16.8375 11.2875C17.0292 11.4792 17.125 11.7167 17.125 12V13C17.125 13.2833 17.0292 13.5208 16.8375 13.7125C16.6458 13.9042 16.4083 14 16.125 14H14.125V15H17.125V16H13.125ZM0 14L4.625 6.725L0.325 0H2.975L6.075 5H6.175L9.25 0H11.925L7.6 6.725L12.25 14H9.575L6.175 8.575H6.075L2.675 14H0Z"
								/>
							</svg>
						</button>

						<button
							@click="editor.chain().focus().toggleSuperscript().run()"
							:class="{ 'is-active': editor.isActive('superscript') }"
						>
							<svg
								width="18"
								height="16"
								viewBox="0 0 18 16"
								xmlns="http://www.w3.org/2000/svg"
							>
								<path
									d="M13.125 5V3C13.125 2.71667 13.2208 2.47917 13.4125 2.2875C13.6042 2.09583 13.8417 2 14.125 2H16.125V1H13.125V0H16.125C16.4083 0 16.6458 0.0958333 16.8375 0.2875C17.0292 0.479167 17.125 0.716667 17.125 1V2C17.125 2.28333 17.0292 2.52083 16.8375 2.7125C16.6458 2.90417 16.4083 3 16.125 3H14.125V4H17.125V5H13.125ZM0 16L4.625 8.725L0.325 2H2.975L6.075 7H6.175L9.25 2H11.925L7.6 8.725L12.25 16H9.575L6.175 10.575H6.075L2.675 16H0Z"
								/>
							</svg>
						</button>

						<button
							@click="editor.chain().focus().unsetAllMarks().clearNodes().run()"
							:class="{ 'is-active': editor.isActive('superscript') }"
						>
							<svg
								xmlns="http://www.w3.org/2000/svg"
								height="24px"
								viewBox="0 -960 960 960"
								width="24px"
							>
								<path
									d="m528-546-93-93-121-121h486v120H568l-40 94ZM792-56 460-388l-80 188H249l119-280L56-792l56-56 736 736-56 56Z"
								/>
							</svg>
						</button>
					</div>
				</template>

				<template v-if="options.includes('align')">
					<!-- Выравнивание -->
					<div class="tiptap__buttons-item">
						<button
							@click="editor.chain().focus().setTextAlign('left').run()"
							:class="{ 'is-active': editor.isActive({ textAlign: 'left' }) }"
						>
							<svg
								width="18"
								height="18"
								viewBox="0 0 18 18"
								xmlns="http://www.w3.org/2000/svg"
							>
								<path
									d="M0 18V16H18V18H0ZM0 14V12H12V14H0ZM0 10V8H18V10H0ZM0 6V4H12V6H0ZM0 2V0H18V2H0Z"
								/>
							</svg>
						</button>
						<button
							@click="editor.chain().focus().setTextAlign('center').run()"
							:class="{ 'is-active': editor.isActive({ textAlign: 'center' }) }"
						>
							<svg
								width="18"
								height="18"
								viewBox="0 0 18 18"
								xmlns="http://www.w3.org/2000/svg"
							>
								<path
									d="M0 18V16H18V18H0ZM4 14V12H14V14H4ZM0 10V8H18V10H0ZM4 6V4H14V6H4ZM0 2V0H18V2H0Z"
								/>
							</svg>
						</button>
						<button
							@click="editor.chain().focus().setTextAlign('right').run()"
							:class="{ 'is-active': editor.isActive({ textAlign: 'right' }) }"
						>
							<svg
								width="18"
								height="18"
								viewBox="0 0 18 18"
								xmlns="http://www.w3.org/2000/svg"
							>
								<path
									d="M0 2V0H18V2H0ZM6 6V4H18V6H6ZM0 10V8H18V10H0ZM6 14V12H18V14H6ZM0 18V16H18V18H0Z"
								/>
							</svg>
						</button>
						<button
							@click="editor.chain().focus().setTextAlign('justify').run()"
							:class="{ 'is-active': editor.isActive({ textAlign: 'justify' }) }"
						>
							<svg
								width="18"
								height="18"
								viewBox="0 0 18 18"
								xmlns="http://www.w3.org/2000/svg"
							>
								<path
									d="M0 18V16H18V18H0ZM0 14V12H18V14H0ZM0 10V8H18V10H0ZM0 6V4H18V6H0ZM0 2V0H18V2H0Z"
								/>
							</svg>
						</button>
					</div>
				</template>

				<template v-if="options.includes('list')">
					<!-- Списки -->
					<div class="tiptap__buttons-item">
						<button
							@click="editor.chain().focus().toggleBlockquote().run()"
							:class="{ 'is-active': editor.isActive('blockquote') }"
						>
							<svg
								width="20"
								height="14"
								viewBox="0 0 17 12"
								xmlns="http://www.w3.org/2000/svg"
							>
								<path
									d="M1.7 12L4 8C2.9 8 1.95833 7.60833 1.175 6.825C0.391667 6.04167 0 5.1 0 4C0 2.9 0.391667 1.95833 1.175 1.175C1.95833 0.391667 2.9 0 4 0C5.1 0 6.04167 0.391667 6.825 1.175C7.60833 1.95833 8 2.9 8 4C8 4.38333 7.95417 4.7375 7.8625 5.0625C7.77083 5.3875 7.63333 5.7 7.45 6L4 12H1.7ZM10.7 12L13 8C11.9 8 10.9583 7.60833 10.175 6.825C9.39167 6.04167 9 5.1 9 4C9 2.9 9.39167 1.95833 10.175 1.175C10.9583 0.391667 11.9 0 13 0C14.1 0 15.0417 0.391667 15.825 1.175C16.6083 1.95833 17 2.9 17 4C17 4.38333 16.9542 4.7375 16.8625 5.0625C16.7708 5.3875 16.6333 5.7 16.45 6L13 12H10.7ZM4 5.5C4.41667 5.5 4.77083 5.35417 5.0625 5.0625C5.35417 4.77083 5.5 4.41667 5.5 4C5.5 3.58333 5.35417 3.22917 5.0625 2.9375C4.77083 2.64583 4.41667 2.5 4 2.5C3.58333 2.5 3.22917 2.64583 2.9375 2.9375C2.64583 3.22917 2.5 3.58333 2.5 4C2.5 4.41667 2.64583 4.77083 2.9375 5.0625C3.22917 5.35417 3.58333 5.5 4 5.5ZM13 5.5C13.4167 5.5 13.7708 5.35417 14.0625 5.0625C14.3542 4.77083 14.5 4.41667 14.5 4C14.5 3.58333 14.3542 3.22917 14.0625 2.9375C13.7708 2.64583 13.4167 2.5 13 2.5C12.5833 2.5 12.2292 2.64583 11.9375 2.9375C11.6458 3.22917 11.5 3.58333 11.5 4C11.5 4.41667 11.6458 4.77083 11.9375 5.0625C12.2292 5.35417 12.5833 5.5 13 5.5Z"
								/>
							</svg>
						</button>

						<button
							@click="editor.chain().focus().toggleBulletList().run()"
							:class="{ 'is-active': editor.isActive('bulletList') }"
						>
							<svg
								width="18"
								height="16"
								viewBox="0 0 18 16"
								xmlns="http://www.w3.org/2000/svg"
							>
								<path
									d="M6 15V13H18V15H6ZM6 9V7H18V9H6ZM6 3V1H18V3H6ZM2 16C1.45 16 0.979167 15.8042 0.5875 15.4125C0.195833 15.0208 0 14.55 0 14C0 13.45 0.195833 12.9792 0.5875 12.5875C0.979167 12.1958 1.45 12 2 12C2.55 12 3.02083 12.1958 3.4125 12.5875C3.80417 12.9792 4 13.45 4 14C4 14.55 3.80417 15.0208 3.4125 15.4125C3.02083 15.8042 2.55 16 2 16ZM2 10C1.45 10 0.979167 9.80417 0.5875 9.4125C0.195833 9.02083 0 8.55 0 8C0 7.45 0.195833 6.97917 0.5875 6.5875C0.979167 6.19583 1.45 6 2 6C2.55 6 3.02083 6.19583 3.4125 6.5875C3.80417 6.97917 4 7.45 4 8C4 8.55 3.80417 9.02083 3.4125 9.4125C3.02083 9.80417 2.55 10 2 10ZM2 4C1.45 4 0.979167 3.80417 0.5875 3.4125C0.195833 3.02083 0 2.55 0 2C0 1.45 0.195833 0.979167 0.5875 0.5875C0.979167 0.195833 1.45 0 2 0C2.55 0 3.02083 0.195833 3.4125 0.5875C3.80417 0.979167 4 1.45 4 2C4 2.55 3.80417 3.02083 3.4125 3.4125C3.02083 3.80417 2.55 4 2 4Z"
								/>
							</svg>
						</button>

						<button
							@click="editor.chain().focus().toggleOrderedList().run()"
							:class="{ 'is-active': editor.isActive('orderedList') }"
						>
							<svg
								width="22"
								height="20"
								viewBox="0 0 18 20"
								xmlns="http://www.w3.org/2000/svg"
							>
								<path
									d="M0 20V18.5H2.5V17.75H1V16.25H2.5V15.5H0V14H3C3.28333 14 3.52083 14.0958 3.7125 14.2875C3.90417 14.4792 4 14.7167 4 15V16C4 16.2833 3.90417 16.5208 3.7125 16.7125C3.52083 16.9042 3.28333 17 3 17C3.28333 17 3.52083 17.0958 3.7125 17.2875C3.90417 17.4792 4 17.7167 4 18V19C4 19.2833 3.90417 19.5208 3.7125 19.7125C3.52083 19.9042 3.28333 20 3 20H0ZM0 13V10.25C0 9.96667 0.0958333 9.72917 0.2875 9.5375C0.479167 9.34583 0.716667 9.25 1 9.25H2.5V8.5H0V7H3C3.28333 7 3.52083 7.09583 3.7125 7.2875C3.90417 7.47917 4 7.71667 4 8V9.75C4 10.0333 3.90417 10.2708 3.7125 10.4625C3.52083 10.6542 3.28333 10.75 3 10.75H1.5V11.5H4V13H0ZM1.5 6V1.5H0V0H3V6H1.5ZM6 17V15H18V17H6ZM6 11V9H18V11H6ZM6 5V3H18V5H6Z"
								/>
							</svg>
						</button>
					</div>
				</template>

				<template v-if="options.includes('link')">
					<!-- Ссылки -->
					<div class="tiptap__buttons-item">
						<button
							@click="insertBlockLink('default', 'Введите ссылку')"
							:class="{ 'is-active': editor.isActive('link') }"
						>
							<svg
								width="20"
								height="10"
								viewBox="0 0 20 10"
								xmlns="http://www.w3.org/2000/svg"
							>
								<path
									d="M9 10H5C3.61667 10 2.4375 9.5125 1.4625 8.5375C0.4875 7.5625 0 6.38333 0 5C0 3.61667 0.4875 2.4375 1.4625 1.4625C2.4375 0.4875 3.61667 0 5 0H9V2H5C4.16667 2 3.45833 2.29167 2.875 2.875C2.29167 3.45833 2 4.16667 2 5C2 5.83333 2.29167 6.54167 2.875 7.125C3.45833 7.70833 4.16667 8 5 8H9V10ZM6 6V4H14V6H6ZM11 10V8H15C15.8333 8 16.5417 7.70833 17.125 7.125C17.7083 6.54167 18 5.83333 18 5C18 4.16667 17.7083 3.45833 17.125 2.875C16.5417 2.29167 15.8333 2 15 2H11V0H15C16.3833 0 17.5625 0.4875 18.5375 1.4625C19.5125 2.4375 20 3.61667 20 5C20 6.38333 19.5125 7.5625 18.5375 8.5375C17.5625 9.5125 16.3833 10 15 10H11Z"
								/>
							</svg>
						</button>

						<button @click="insertBlockLink('email', 'Введите email')">
							<svg
								xmlns="http://www.w3.org/2000/svg"
								height="24px"
								viewBox="0 -960 960 960"
								width="24px"
							>
								<path
									d="M160-160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h640q33 0 56.5 23.5T880-720v480q0 33-23.5 56.5T800-160H160Zm320-280L160-640v400h640v-400L480-440Zm0-80 320-200H160l320 200ZM160-640v-80 480-400Z"
								/>
							</svg>
						</button>

						<button @click="insertBlockLink('phone', 'Введите номер телефона')">
							<svg
								xmlns="http://www.w3.org/2000/svg"
								height="24px"
								viewBox="0 -960 960 960"
								width="24px"
							>
								<path
									d="M798-120q-125 0-247-54.5T329-329Q229-429 174.5-551T120-798q0-18 12-30t30-12h162q14 0 25 9.5t13 22.5l26 140q2 16-1 27t-11 19l-97 98q20 37 47.5 71.5T387-386q31 31 65 57.5t72 48.5l94-94q9-9 23.5-13.5T670-390l138 28q14 4 23 14.5t9 23.5v162q0 18-12 30t-30 12ZM241-600l66-66-17-94h-89q5 41 14 81t26 79Zm358 358q39 17 79.5 27t81.5 13v-88l-94-19-67 67ZM241-600Zm358 358Z"
								/>
							</svg>
						</button>

						<button
							@click="editor.chain().focus().unsetLink().run()"
							:disabled="!editor.isActive('link')"
						>
							<svg
								width="21"
								height="20"
								viewBox="0 0 21 20"
								xmlns="http://www.w3.org/2000/svg"
							>
								<path
									d="M17.85 13.65L16.35 12.1C17.0167 11.9167 17.5583 11.5625 17.975 11.0375C18.3917 10.5125 18.6 9.9 18.6 9.2C18.6 8.36667 18.3083 7.65833 17.725 7.075C17.1417 6.49167 16.4333 6.2 15.6 6.2H11.6V4.2H15.6C16.9833 4.2 18.1625 4.6875 19.1375 5.6625C20.1125 6.6375 20.6 7.81667 20.6 9.2C20.6 10.15 20.3542 11.025 19.8625 11.825C19.3708 12.625 18.7 13.2333 17.85 13.65ZM14.45 10.2L12.45 8.2H14.6V10.2H14.45ZM18.4 19.8L0 1.4L1.4 0L19.8 18.4L18.4 19.8ZM9.6 14.2H5.6C4.21667 14.2 3.0375 13.7125 2.0625 12.7375C1.0875 11.7625 0.6 10.5833 0.6 9.2C0.6 8.05 0.95 7.025 1.65 6.125C2.35 5.225 3.25 4.63333 4.35 4.35L6.2 6.2H5.6C4.76667 6.2 4.05833 6.49167 3.475 7.075C2.89167 7.65833 2.6 8.36667 2.6 9.2C2.6 10.0333 2.89167 10.7417 3.475 11.325C4.05833 11.9083 4.76667 12.2 5.6 12.2H9.6V14.2ZM6.6 10.2V8.2H8.225L10.2 10.2H6.6Z"
								/>
							</svg>
						</button>
					</div>
				</template>

				<template v-if="options.includes('image')">
					<!-- Вставка контента -->
					<div class="tiptap__buttons-item">
						<button @click="openImage">
							<svg
								xmlns="http://www.w3.org/2000/svg"
								height="24px"
								viewBox="0 -960 960 960"
								width="24px"
							>
								<path
									d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm0-80h560v-560H200v560Zm40-80h480L570-480 450-320l-90-120-120 160Zm-40 80v-560 560Z"
								/>
							</svg>
						</button>
					</div>
				</template>
			</div>

			<div class="tiptap__buttons-footer">
				<!-- История -->
				<div class="tiptap__buttons-item">
					<button
						@click="editor.chain().focus().undo().run()"
						:disabled="!editor.can().undo()"
					>
						<svg
							width="16"
							height="15"
							viewBox="0 0 16 15"
							xmlns="http://www.w3.org/2000/svg"
						>
							<path
								d="M3 15V13H10.1C11.15 13 12.0625 12.6667 12.8375 12C13.6125 11.3333 14 10.5 14 9.5C14 8.5 13.6125 7.66667 12.8375 7C12.0625 6.33333 11.15 6 10.1 6H3.8L6.4 8.6L5 10L0 5L5 0L6.4 1.4L3.8 4H10.1C11.7167 4 13.1042 4.525 14.2625 5.575C15.4208 6.625 16 7.93333 16 9.5C16 11.0667 15.4208 12.375 14.2625 13.425C13.1042 14.475 11.7167 15 10.1 15H3Z"
							/>
						</svg>
					</button>
					<button
						@click="editor.chain().focus().redo().run()"
						:disabled="!editor.can().redo()"
					>
						<svg
							width="16"
							height="15"
							viewBox="0 0 16 15"
							xmlns="http://www.w3.org/2000/svg"
						>
							<path
								d="M5.9 15C4.28333 15 2.89583 14.475 1.7375 13.425C0.579167 12.375 0 11.0667 0 9.5C0 7.93333 0.579167 6.625 1.7375 5.575C2.89583 4.525 4.28333 4 5.9 4H12.2L9.6 1.4L11 0L16 5L11 10L9.6 8.6L12.2 6H5.9C4.85 6 3.9375 6.33333 3.1625 7C2.3875 7.66667 2 8.5 2 9.5C2 10.5 2.3875 11.3333 3.1625 12C3.9375 12.6667 4.85 13 5.9 13H13V15H5.9Z"
							/>
						</svg>
					</button>
				</div>

				<!-- Счётчик -->
				<div
					:class="{
						'character-count': true,
						'character-count--warning': editor.storage.characterCount.characters() === limit,
					}"
				>
					<svg height="20" width="20" viewBox="0 0 20 20">
						<circle r="10" cx="10" cy="10" fill="#e9ecef" />
						<circle
							r="5"
							cx="10"
							cy="10"
							fill="transparent"
							stroke="currentColor"
							stroke-width="10"
							:stroke-dasharray="`calc(${percentage} * 31.4 / 100) 31.4`"
							transform="rotate(-90) translate(-20)"
						/>
						<circle r="6" cx="10" cy="10" fill="white" />
					</svg>

					{{ editor.storage.characterCount.characters() }} / {{ limit }} символов
					<br />
					{{ editor.storage.characterCount.words() }} слов
				</div>
			</div>
		</div>

		<div
			class="tiptap__editor"
			:class="{ 'disabled': !editable, 'error': error }"
			:style="{ '--min-height': `${minHeight}px` }"
		>
			<div class="tiptap__error" v-if="error">
				<slot name="error"></slot>
			</div>

			<editor-content :editor="editor" />
		</div>
	</div>
</template>

<script>
import { BubbleMenu, Editor, EditorContent } from "@tiptap/vue-3";

import Blockquote from "@tiptap/extension-blockquote";
import Image from "@tiptap/extension-image";
import CharacterCount from "@tiptap/extension-character-count";

import Underline from "@tiptap/extension-underline";
import Subscript from "@tiptap/extension-subscript";
import Superscript from "@tiptap/extension-superscript";
import Link from "@tiptap/extension-link";
import TextAlign from "@tiptap/extension-text-align";
import Placeholder from "@tiptap/extension-placeholder";

import StarterKit from "@tiptap/starter-kit";

import validate from "../../services/validate";
import axios from "axios";

export default {
	components: {
		BubbleMenu,
		EditorContent,
	},
	props: {
		modelValue: {
			type: String,
			default: "",
			required: true,
		},
		editable: {
			type: Boolean,
			default: true,
		},
		options: {
			type: Array,
			default: ["format", "align", "list", "link", "image"],
		},
		minHeight: {
			type: Number,
			default: 0,
		},
		limit: {
			type: Number,
			default: 500,
		},
		placeholder: {
			type: String,
			default: "Введите текст",
		},
		error: {
			type: Boolean,
			default: false,
		},
	},
	data() {
		return {
			disabled: {
				tiptap: {
					image: false,
				},
			},

			/* Модальное окно */
			modalImage: {
				thin: true,
				clamped: false,
				values: {
					title: "",
					look: "default",
				},
			},

			/* Форма */
			currentImage: {
				errors: {
					file: {
						message: "",
						status: false,
					},
				},
				data: {
					file: {
						value: "",
						edited: false,
					},
				},
			},

			editor: null,
			isEditable: true,
		};
	},
	watch: {
		isEditable(value) {
			this.editor.setEditable(value);
		},

		modelValue: {
			handler(newValue) {
				if (this.editor && this.editor.getHTML() !== newValue) {
					this.editor.commands.setContent(newValue, false);
				}
			},
			deep: true,
		},
	},
	computed: {
		percentage() {
			return Math.round((100 / this.limit) * this.editor.storage.characterCount.characters());
		},
	},
	methods: {
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                 Модальное окно                    |*/
		/* |___________________________________________________|*/
		/* Открытие модального окна */
		openModal(name, title, look) {
			this[name].values.title = title;
			this[name].values.look = look;

			this.$refs[name].open();
		},

		/* Открытие модального окна для добавления */
		openImage() {
			this.$refs.fileUpload.value = null;

			this.openModal("modalImage", "КАРТИНКА", "default");
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                   База данных                     |*/
		/* |___________________________________________________|*/
		/* Открытие модального окна */
		uploadImage() {
			if (
				validate.checkInputsAll(this.currentImage, [
					{
						key: "file",
						type: "file",
						value: this.$refs.fileUpload,
						formats: ["jpg", "jpeg", "png", "webp"],
					},
				])
			)
				return;

			/* Загрузка файла */
			let formData = new FormData();
			formData.append("file", this.$refs.fileUpload.files[0]);
			formData.append("type", "news");
			formData.append("formats", ["png", "jpg", "jpeg", "webp"]);

			this.disabled.tiptap.image = true;

			axios({
				method: "post",
				url: `${this.$store.getters.urlApi}` + `upload-file`,
				headers: {
					"Content-Type": "multipart/form-data",
					Authorization: `Bearer ${localStorage.getItem("token")}`,
				},
				data: formData,
			})
				.then((response) => {
					if (response.data.status) {
						this.insertBlockImage(response.data.data);

						this.$refs.modalImage.close();
					} else {
						this.$store.commit("addDebugger", {
							title: "Ошибка.",
							body: response.data.message,
							type: "error",
						});
					}
				})
				.catch((error) => {
					this.$store.commit("addDebugger", {
						title: "Ошибка.",
						body: response.data.message,
						type: "error",
					});
				})
				.finally(() => {
					this.disabled.tiptap.image = false;
				});
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                    Редактор                       |*/
		/* |___________________________________________________|*/
		/* Редактирование ссылки */
		insertBlockLink(type, title) {
			let previousUrl = this.editor.getAttributes("link").href;

			if (previousUrl !== undefined && typeof previousUrl === "string") {
				switch (type) {
					case "email":
						previousUrl = previousUrl.replace("mailto:", "");
						break;
					case "phone":
						previousUrl = previousUrl.replace("tel:", "");
						break;

					default:
						break;
				}
			}

			let url = window.prompt(title, previousUrl);

			// cancelled
			if (url === null) {
				return;
			}

			// empty
			if (url === "") {
				this.editor.chain().focus().extendMarkRange("link").unsetLink().run();

				return;
			}

			let prefix = "";

			switch (type) {
				case "email":
					prefix = "mailto:";
					break;
				case "phone":
					prefix = "tel:";
					break;
				default:
					break;
			}

			// Обновление ссылки
			this.editor
				.chain()
				.focus()
				.extendMarkRange("link")
				.setLink({ href: `${prefix}` + url })
				.run();
		},

		/* Добавление картинки */
		insertBlockImage(url) {
			this.editor.chain().focus().setImage({ src: url }).run();
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                    Экспорт                        |*/
		/* |___________________________________________________|*/
		/* Экспорт в html */
		getSymbols() {
			return this.editor.storage.characterCount.characters();
		},

		/* Экспорт в html */
		getHTML() {
			return this.editor.getHTML();
		},

		/* Экспорт в JSON */
		getJSON() {
			return this.editor.getJSON();
		},
	},
	mounted() {
		this.editor = new Editor({
			onUpdate: ({ editor }) => {
				const html = editor.getHTML();
				if (html !== this.modelValue) {
					this.$emit("update:modelValue", html);
				}
			},

			// Расширения Tiptap
			extensions: [
				StarterKit.configure({
					headings: { levels: [1, 2, 3] },
				}),
				Underline,
				Link.configure({
					openOnClick: false,
					defaultProtocol: "https",
				}),
				Subscript,
				Superscript,
				TextAlign.configure({
					types: ["heading", "paragraph", "image"],
					defaultAlignment: "left",
				}),
				CharacterCount.configure({
					limit: this.limit,
				}),
				Placeholder.configure({
					placeholder: this.placeholder,
				}),
				Image,
			],

			// Содержимое редактора
			content: this.modelValue,

			// Установка фокуса при загрузке редактора
			autofocus: false,

			// Редактирование (по умолчанию true)
			editable: this.editable,

			// Запретить загрузку CSS по умолчанию (чего в любом случае не так уж много)
			injectCSS: false,
		});
	},
	beforeUnmount() {
		this.editor.destroy();
	},
};
</script>

<style>
/* Ваши стили редактора */
.editor {
	/* Обязательное правило */
	white-space: pre-wrap;
	word-wrap: break-word;
}

/* Дополнительные рекомендации для ProseMirror */
.ProseMirror {
	outline: none;
	white-space: pre-wrap;

	min-height: var(--min-height);
}

.ProseMirror p {
	margin: 0;
}

/* Кнопки */
.tiptap__buttons {
	display: flex;
	flex-direction: column;
	flex-wrap: wrap;
	gap: 10px;

	margin-bottom: 10px;
}

.tiptap__buttons-body {
	display: flex;
	flex-wrap: wrap;
	gap: 20px;
}

.tiptap__buttons-footer {
	display: flex;
	justify-content: space-between;
	gap: 20px;
}

.tiptap__buttons-item {
	display: flex;
	flex-wrap: wrap;
	gap: 5px;
}

.tiptap__buttons-item button {
	cursor: pointer;
	display: flex;
	justify-content: center;
	align-items: center;

	border: var(--input-border);
	border-radius: 100%;

	width: 40px;
	height: 40px;

	background-color: var(--secondary-background-color-hover);

	transition: all 0.2s;
}

.tiptap__buttons-item button.is-active {
	border: var(--input-border-focus);
}

.tiptap__buttons-item button svg {
	fill: var(--default-font-color);
}

/* Редактор */
.tiptap__editor {
	position: relative;
	border: var(--input-border);
	border-radius: var(--input-border-radius);
	padding: 10px;
	outline: 0px solid rgba(0, 0, 0, 0.2);

	background-color: var(--secondary-background-color-hover);

	transition: all 0.2s;
}

.tiptap__editor.disabled {
	border: 0px solid rgba(0, 0, 0, 0.25);
	border-radius: 0px;
	padding: 0px;

	background-color: rgba(0, 0, 0, 0);
}

.tiptap {
	border: 0px solid rgba(0, 0, 0, 0.25);
	border-radius: 0px;
	padding: 0px;
	outline: 0px;
}

.tiptap.disabled {
	border: 0px solid rgba(0, 0, 0, 0.25);
}

.tiptap p {
	margin: 10px 0px;
	font-size: 1.125rem;
}

.tiptap p:is(:first-child, :last-child) {
	margin: 0px 0px;
}

.tiptap p.is-editor-empty:first-child::before {
	color: var(--input-placeholder-color);
	content: attr(data-placeholder);
	float: left;
	height: 0;
	pointer-events: none;
}

.tiptap blockquote {
	border-left: 3px solid var(--default-border-color);
	margin: 0px;
	padding-left: 10px;
}

.tiptap :is(h1, h2, h3, h4, h5, h6) {
	color: var(--primary-color);
	margin: 10px 0px;
}

.tiptap h1 {
	font-size: 1.5rem;
}

.tiptap h2 {
	font-size: 1.25rem;
}

.tiptap h3 {
	font-size: 1.125rem;
}

.tiptap a {
	color: var(--primary-color);
}

.tiptap img {
	width: 100%;
	object-fit: contain;
	height: 500px;
}

.tiptap ul {
	margin-left: 20px;
}

.tiptap li {
	margin-top: 10px;
	margin-bottom: 10px;
}

.tiptap img.ProseMirror-selectednode {
	outline: 2px solid var(--primary-color);
}

.tiptap__editor.disabled img.ProseMirror-selectednode {
	outline: 0px;
}

/* Вслпывающее меню */
.bubble__menu {
	background-color: white;
	border: 0px solid gray;
	border-radius: 0.7rem;
	box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
	display: flex;
	gap: 5px;
	padding: 5px;
}

.bubble__menu button {
	cursor: pointer;
	background-color: unset;
	border: 0px;
	border-radius: 10px;

	padding: 5px 10px;
}

.bubble__menu button:hover {
	background-color: rgba(0, 0, 0, 0.1);
}

.bubble__menu button.is-active {
	background-color: rgba(0, 0, 0, 0.1);
}

.bubble__menu button.is-active:hover {
	background-color: rgba(0, 0, 0, 0.1);
}

.tiptap__toolbar {
	display: flex;
	justify-content: space-between;
	gap: 5px;
}

/* Character count */
.character-count {
	align-items: center;
	color: var(--gray-5);
	display: flex;
	font-size: 1rem;
	gap: 10px;
}

.character-count svg {
	color: var(--purple);
}

.character-count svg circle:first-child {
	fill: var(--secondary-background-color-hover) !important;
}

.character-count svg circle:last-child {
	fill: var(--secondary-background-color) !important;
}

.character-count.--warning,
.character-count.--warning svg {
	color: var(--red);
}

/* error */
.tiptap__error {
	position: absolute;
   user-select: none;
   position: absolute;
   right: 10px;
   top: -15px;

   border: var(--error-border);
   border-radius: var(--error-border-radius);
   padding: var(--error-padding);

   background-color: var(--error-background-color);
   color: var(--error-color);

   animation: show-translate-x 0.2s ease;
}
</style>
