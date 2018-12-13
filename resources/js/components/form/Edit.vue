<template>
    <div v-if="!loading">
        <heading class="mb-3">{{__('Edit')}} {{ singularName }}</heading>

        <card class="overflow-hidden">
            <form v-if="fields" @submit.prevent="updateResource" autocomplete="off">
                <!-- Validation Errors -->
                <validation-errors :errors="validationErrors" />
                <a :href='"/" + singularName.toLowerCase() + "/" + resourceId' target="_blank">Preview</a>
                <a :href='"/page-builder/page/" + resourceId'>Edit with page builder</a>
                <!-- Fields -->
                <div v-for="field in fields">
                    <component
                        @file-deleted="updateLastRetrievedAtTimestamp"
                        :is="'form-' + field.component"
                        :errors="validationErrors"
                        :resource-id="resourceId"
                        :resource-name="resourceName"
                        :field="field"
                    />
                </div>

                <!-- Update Button -->
                <div class="bg-30 flex px-8 py-4">
                    <button type="button" dusk="update-and-continue-editing-button" @click="updateAndContinueEditing" class="ml-auto btn btn-default btn-primary mr-3">
                        {{__('Update &amp; Continue Editing')}}
                    </button>

                    <button dusk="update-button" class="btn btn-default btn-primary">
                        {{__('Update')}} {{ singularName }}
                    </button>
                </div>
            </form>
        </card>
    </div>
</template>


<script>
import Update from '@nova/views/Update'

export default {
    mixins: [Update]
}
</script>
