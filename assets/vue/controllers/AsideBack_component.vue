<script setup>
const Routes = {
  home: "/backoffice",
  clients: "/customers",
  empleats: "/employees",
  reserves: "/reservations",
  vehicles: "/vehicles",
  proveidors: "/providers",
  factures: "/invoices",
  comandes: "/orders",
};
defineProps({
  Routes: Object,
})
</script>

<template>
  <nav class="bg-quaternary-lm">
    <ul class="m-0 list-styled fs-5 d-flex flex-column text-sm-start text-center">
      <li class="pe-3 py-4 bg-tertiary-lm ps-sm-5">
        <a :href="Routes.home" class="text-decoration-none text-white">
          <i class="bi bi-file-earmark"></i> Factures
        </a>
      </li>
    </ul>
  </nav>
</template>