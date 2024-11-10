<template>
    <div class="offcanvas offcanvas-end custom-offcanvas-width" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
  <div class="offcanvas-header">
    <h5 id="offcanvasRightLabel">Recent Transaction</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <table class="table">
     <thead>
        <tr>
            <th>Id</th>
            <th>Date</th>
            <th>Order Mode</th>
            <th>Amount</th>
            <th>Action</th>
        </tr>
     </thead>
     <tbody>
        <tr v-for="(transaction,index) in transactions" :key="index">
         <td>{{ transaction.invoice_id }}</td>
         <td>{{ transaction.sell_date }}</td>
         <td>{{ transaction.order_mode_name }}
            <br>
            {{ transaction.table_name?transaction.table_name:null }}
         </td>
         <td>{{ transaction.grand_total_price }}</td>
         <td><a href="#" @click="changeCurrentId(transaction.id)"><i class="fas fa-edit"></i></a></td>

        </tr>
     </tbody>
    </table>
  </div>
</div>
</template>
<script>
export default {
    props: {
        transactions: {
        }},
      methods:{
        changeCurrentId(id){
            this.$emit('changeCurrentId',id);
        }
      }

}
</script>

<style scoped>
.custom-offcanvas-width {
  width: 50vw; /* Set the desired width, e.g., 50% of the viewport */
  max-width: 100%; /* Ensure it does not exceed viewport width */
}</style>
