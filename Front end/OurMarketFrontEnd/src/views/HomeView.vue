<template>
  <div>
    <SearchAd @search="search" ></SearchAd>
    <AvailableAdList :ads="ads"></AvailableAdList>
  </div>
</template>

<script>
import SearchAd from '@/components/HomePage/SearchAd.vue'
import AvailableAdList from '@/components/HomePage/AvailableAdList.vue'
import axios from '@/axios-auth.js'

export default {
  name: 'Home',
  components: {
    SearchAd,
    AvailableAdList
  },
  data(){
    return {
      ads: [],
    }
  },
  methods: {
    search(searchQuery) {
      if(searchQuery !== '')
      this.getAds('/ads?name=' + searchQuery);
      else
      this.getAds('/ads');
    }
    ,getAds($url){
      axios.get($url)
      .then(response => {
       this.ads=response.data;
      })
      .catch(error => {
        console.log(error.response.data.errorMessage);
      });
    }
  }
  ,mounted(){
    this.getAds('/ads');
  }
}
</script>
