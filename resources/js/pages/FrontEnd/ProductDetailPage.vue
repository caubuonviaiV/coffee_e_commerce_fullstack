<template>
    <Layout>
        <template v-slot:content>
            <div class="container margin_60_40" style="margin-top: 7em">
                <div class="row">
                    <div class="col-lg-6">
                        <p
                            v-for="image in product.images"
                            :key="image.id"
                            @change="previewFiles($event)"
                        >
                            <a
                                :href="image.url"
                                :title="image.name"
                                data-effect="mfp-zoom-in"
                            >
                                <img
                                    style="width: 600px; height: 600px"
                                    :src="image.url"
                                    :alt="image.name"
                                    class="img-fluid"
                                />
                            </a>
                        </p>
                    </div>

                    <Vue3StickySidebar
                        class="sidebar col-lg-6"
                        containerSelector=".container"
                        innerWrapperSelector=".sidebar__inner"
                    >
                        <div class="prod_info">
                            <span
                                class="rating"
                                v-if="
                                    product.total_rating && product.total_number
                                "
                            >
                                <i
                                    class="icon_star"
                                    v-for="n in 5"
                                    :key="n"
                                    :class="{
                                        voted:
                                            n <=
                                            Math.round(
                                                product.total_number /
                                                    product.total_rating
                                            ),
                                    }"
                                ></i>
                                <em
                                    >{{ product.reviews?.length || 0 }} Đánh
                                    giá</em
                                >
                            </span>
                            <h1>{{ product.name }}</h1>
                            <p>{{ product.content }}</p>
                            <div class="prod_options">
                                <div
                                    class="row"
                                    v-if="
                                        product.sizes &&
                                        product.sizes.length > 0
                                    "
                                >
                                    <label
                                        class="col-xl-5 col-lg-5 col-md-6 col-6"
                                        ><strong>Chọn size </strong>(bắt
                                        buộc)</label
                                    >
                                    <div
                                        class="col-xl-4 col-lg-5 col-md-6 col-6"
                                    >
                                        <div class="custom-select-form">
                                            <el-select
                                                v-model="selectedSize"
                                                @change="updatePrice"
                                            >
                                                <el-option
                                                    v-for="size in product.sizes"
                                                    :key="size.id"
                                                    :label="
                                                        size.name +
                                                        ' + (' +
                                                        size.increases +
                                                        'đ)'
                                                    "
                                                    :value="size"
                                                ></el-option>
                                            </el-select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <label
                                        class="col-xl-5 col-lg-5 col-md-6 col-6"
                                        ><strong>SỐ LƯỢNG</strong></label
                                    >
                                    <div
                                        class="col-xl-4 col-lg-5 col-md-6 col-6"
                                    >
                                        <Incrementer v-model="quantity" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-5 col-md-6">
                                    <div class="price_main">
                                        <span class="new_price"
                                            >{{ calculatedPrice }}đ</span
                                        >
                                        <span
                                            v-if="product.price"
                                            class="percentage"
                                            >-{{ discountPercentage }}%</span
                                        >
                                        <span
                                            v-if="product.price"
                                            class="old_price"
                                            >{{
                                                product.price
                                                    | product.price_sale
                                            }}
                                            đ</span
                                        >
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6">
                                    <div class="btn_add_to_cart">
                                        <button
                                            @click="addToCart"
                                            class="btn_1"
                                        >
                                            Thêm vào giỏ hàng
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </Vue3StickySidebar>
                </div>
            </div>

            <div class="tabs_product">
                <div class="container">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a
                                id="tab-A"
                                href="#pane-A"
                                class="nav-link active"
                                data-toggle="tab"
                                role="tab"
                                >Mô tả</a
                            >
                        </li>
                        <li class="nav-item">
                            <a
                                id="tab-B"
                                href="#pane-B"
                                class="nav-link"
                                data-toggle="tab"
                                role="tab"
                                >Đánh giá</a
                            >
                        </li>
                    </ul>
                </div>
            </div>
            <div class="tab_content_wrapper">
                <div class="container">
                    <div class="tab-content" role="tablist">
                        <div
                            id="pane-A"
                            class="card tab-pane fade show active"
                            role="tabpanel"
                            aria-labelledby="tab-A"
                        >
                            <div class="card-header" role="tab" id="heading-A">
                                <h5 class="mb-0">
                                    <a
                                        class="collapsed"
                                        data-toggle="collapse"
                                        href="#collapse-A"
                                        aria-expanded="false"
                                        aria-controls="collapse-A"
                                    >
                                        Mô tả
                                    </a>
                                </h5>
                            </div>
                            <div
                                id="collapse-A"
                                class="collapse"
                                role="tabpanel"
                                aria-labelledby="heading-A"
                            >
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h3>Chi tiết</h3>
                                            <p>{{ product.content }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <h3>Specifications (100g)</h3>
                                            <div class="table-responsive">
                                                <table
                                                    class="table table-sm table-striped"
                                                >
                                                    <tbody>
                                                        <!-- <tr v-for="(value, key) in product.specifications" :key="key">
                                                            <td><strong>{{ key }}</strong></td>
                                                            <td>{{ value }}</td>
                                                        </tr> -->
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div
                            id="pane-B"
                            class="card tab-pane fade"
                            role="tabpanel"
                            aria-labelledby="tab-B"
                        >
                            <div class="card-header" role="tab" id="heading-B">
                                <h5 class="mb-0">
                                    <a
                                        class="collapsed"
                                        data-toggle="collapse"
                                        href="#collapse-B"
                                        aria-expanded="false"
                                        aria-controls="collapse-B"
                                    >
                                        Đánh Giá
                                    </a>
                                </h5>
                            </div>
                            <div
                                id="collapse-B"
                                class="collapse"
                                role="tabpanel"
                                aria-labelledby="heading-B"
                            >
                                <div class="card-body">
                                    <div
                                        class="row justify-content-between"
                                        v-for="review in product.reviews"
                                        :key="review.id"
                                    >
                                        <div class="col-lg-6">
                                            <div class="review_content">
                                                <div
                                                    class="clearfix add_bottom_10"
                                                >
                                                    <span class="rating">
                                                        <i
                                                            v-for="star in 5"
                                                            :key="star"
                                                            :class="{
                                                                icon_star: true,
                                                                empty:
                                                                    star >
                                                                    review.rating,
                                                            }"
                                                        ></i>
                                                        <em
                                                            >{{
                                                                review.rating
                                                            }}/5.0</em
                                                        >
                                                    </span>
                                                    <em
                                                        v-if="
                                                            review.active === 1
                                                        "
                                                        >Hiển thị</em
                                                    >
                                                    <em v-else>Ẩn</em>
                                                </div>
                                                <h4>Trung</h4>
                                                <p>{{ review.comment }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-right">
                                        <a
                                            href="leave-review.html"
                                            class="btn_1"
                                            >Để lại đánh giá</a
                                        >
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </Layout>
</template>

<script>
import Layout from "./../../layouts/frontend/Index.vue";
import axios from "axios";
import Vue3StickySidebar from "vue3-sticky-sidebar";
import Incrementer from "../../components/Incrementer.vue";
import { ElSelect, ElOption, ElMessage } from "element-plus";
import { ref, computed, onMounted } from "vue";
import { useCartStore } from "../../stores/useCartStore";
import { useRouter } from "vue-router";

export default {
    name: "ProductDetail",
    components: {
        Layout,
        Vue3StickySidebar,
        Incrementer,
        ElSelect,
        ElOption,
    },
    props: ["id"],
    setup(props) {
        const product = ref({});
        const selectedSize = ref({ increases: 0 });
        const calculatedPrice = ref(0);
        const quantity = ref(1);
        const cartStore = useCartStore();
        const router = useRouter();

        const discountPercentage = computed(() => {
            if (!product.value.price || !product.value.price_sale) return 0;
            return Math.round(
                ((product.value.price - product.value.price_sale) /
                    product.value.price) *
                    100
            );
        });

        const fetchProduct = () => {
            axios
                .get(`/api/products/${props.id}`)
                .then((response) => {
                    product.value = response.data.product;
                    selectedSize.value = product.value.sizes[0];
                    updatePrice();
                })
                .catch((error) => {
                    console.error(
                        "There was an error fetching the product!",
                        error
                    );
                });
        };

        const updatePrice = () => {
            let price = product.value.price_sale || product.value.price;
            let increases = parseFloat(selectedSize.value?.increases) || 0;

            calculatedPrice.value = price + increases;
        };

        const addToCart = (product) => {
            cartStore.addToCart_quantity(product, quantity.value);
            ElMessage.success("Sản phẩm đã được thêm vào giỏ hàng!");
            router.push({ name: "Cart" });
        };
        onMounted(fetchProduct);

        return {
            product,
            selectedSize,
            calculatedPrice,
            quantity,
            discountPercentage,
            fetchProduct,
            updatePrice,
            addToCart,
        };
    },
};
</script>
