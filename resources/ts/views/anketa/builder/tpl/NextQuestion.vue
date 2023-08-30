<template>

    <div v-if="question" class="opt-nest">

        <div class="opt-nest-str" v-for="option in question.recursive_options">

            <i class="h-icon-right"/>
            <a target="_blank"
               :href="`${server}/admin/settings/anketa/${option.next_question}/edit#tab-form-2`">
                            <span class="h-tag h-tag-bg-gray small"
                                  style="cursor: pointer"
                                  v-html="option.recursive_question.slug"
                                  v-tooltip placement="right"
                                  :content="option.recursive_question.question"
                            ></span>
            </a>


            <NextQuestion :question="option.recursive_question" :server="server"/>

        </div>

    </div>

</template>

<script lang="ts">

export default {
    name: 'NextQuestion',
    props: {
        question: Object,
        server: String
    }
};
</script>
