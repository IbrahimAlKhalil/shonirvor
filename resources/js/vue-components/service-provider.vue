<template>
    <tr>
        <td>{{ index }}</td>
    </tr>
</template>

<script>
    export default {
        name: 'service-provider',
        props: ['data', 'index'],
        data: function () {
            return {};
        },
        beforeCreate() {
            console.log('before create');
        }
    };
</script>

<style lang="scss"></style>