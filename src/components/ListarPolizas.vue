<template>
  <div class="contact">
    <h2>Listado de Polizas</h2>

    <div class="card">
      <div class="card-header">Lista Polizas</div>
      <div class="card-body d-flex justify-content-center">
        <table id="tabla" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>ID</th>
              <th>Cliente</th>
              <th>Numero Poliza</th>
              <th>Importe</th>
              <th>Fecha</th>
              <th>Estado</th>
              <th>Observaciones</th>
              <th>Opciones</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="poliza in polizas" :key="poliza.id" :class="{
              'bg-warning': poliza.estado === 'liquidada',
              'bg-danger': poliza.estado === 'anulada',
              'bg-info': poliza.estado === 'a_cuenta',
              'bg-success': poliza.estado === 'cobrada',
              'bg-primary': poliza.estado === 'pre-anulada',
            }">
              <td>
                {{ poliza.id }}
              </td>
              <td><router-link style="text-decoration: none; color: black;"
                  :to="{ name: 'detalles', query: { cliente: poliza.id_cliente } }">{{ poliza.id_cliente }}</router-link>
              </td>
              <td>{{ poliza.numero_poliza }}</td>
              <td>{{ poliza.importe_recibo }}</td>
              <td>{{ new Date(poliza.fecha).toLocaleDateString() }}</td>
              <td>
                <span v-if="poliza.estado === 'liquidada'">Liquidada</span>
                <span v-if="poliza.estado === 'anulada'">Anulada</span>
                <span v-if="poliza.estado === 'a_cuenta'">A cuenta</span>
                <span v-if="poliza.estado === 'cobrada'">Cobrada</span>
                <span v-if="poliza.estado === 'pre-anulada'">Pre-anulada</span>
              </td>

              <td>{{ poliza.observaciones }}</td>
              <td>
                <!-- <button class="btn btn-danger" v-on:click="borrarPoliza(poliza.id)">
                  🗑️
                </button> -->
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#staticBackdrop"
                  @click="almacenarPoliza(poliza)">
                  🗑️
                </button>
                <router-link :to="{ name: 'editarPoliza', query: { id: poliza.id } }"
                  class="btn btn-warning">📝</router-link>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
      aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="staticBackdropLabel">Confirmación de borrado</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <h5>¿Estás seguro que quieres borrar la poliza <b>{{ selecPoliza.id }}</b>?</h5>
            <div class="modal-body">
              <b>Cliente:</b> {{ selecPoliza.id_cliente }}<br>
              <b>Numero poliza:</b> {{ selecPoliza.numero_poliza }}<br>
              <b>Importe:</b> {{ selecPoliza.importe_recibo }}<br>
              <b>Fecha:</b> {{ selecPoliza.fecha }}<br>
              <b>Estado:</b> {{ selecPoliza.estado }}<br>
              <b>Observaciones:</b> {{ selecPoliza.observaciones }}<br>
            </div>


          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Atrás</button>
            <button class="btn btn-danger" data-bs-dismiss="modal" v-on:click="borrarPoliza(selecPoliza.id)">
              Borrar
            </button>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>
<script>
import $ from "jquery";
import "datatables.net";
import "bootstrap/dist/css/bootstrap.min.css";
import "bootstrap/dist/js/bootstrap.bundle.min.js";
export default {
  data() {
    return {
      selecPoliza: [],
      polizas: [],
    };
  },

  created: function () {
    this.consultarPolizas();
  },
  methods: {
    almacenarPoliza(poliza) {
      this.selecPoliza = poliza
    },
    consultarPolizas() {
      fetch("index.php/?listaPolizas")
        .then((resp) => resp.json())
        .then((datosResp) => {
          console.log(datosResp);
          this.polizas = [];
          if (typeof datosResp[0].success === "undefined") {
            //si no es undefined..
            this.polizas = datosResp;
          }
          this.$nextTick(function () {
            $("#tabla").DataTable();
          });
        })
        .catch(console.log);
    },
    borrarPoliza(id) {
      fetch("index.php/?borrarPoliza=" + id)
        .then((resp) => resp.json())
        .then((datosResp) => {
          console.log(datosResp);
          this.consultarPolizas();
        })
        .catch(console.log);
    },
  },
};
</script>